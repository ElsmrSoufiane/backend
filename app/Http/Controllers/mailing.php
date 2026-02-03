<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Mail\VerificationMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use App\Models\User;

class mailing extends Controller
{
    /**
     * Add CORS headers to response
     */
    private function addCorsHeaders($response)
    {
        return $response->header('Access-Control-Allow-Origin', 'http://localhost:3000')
                       ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                       ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                       ->header('Access-Control-Allow-Credentials', 'true');
    }
    public function checkVerification(Request $request)
{
    $this->configureGmail(); // ensure Gmail config is used

    $email = $request->input('email');

    if (!$email) {
        return $this->addCorsHeaders(response()->json([
            'success' => false,
            'message' => 'Email is required'
        ], 400));
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        return $this->addCorsHeaders(response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404));
    }

    return $this->addCorsHeaders(response()->json([
        'success' => true,
        'data' => [
            'user_id' => $user->id,
            'email' => $user->email,
            'verified' => $user->verified,
            'verified_at' => $user->email_verified_at
        ]
    ]));
 }
 private function configureGmail()
{
    // WARNING: Never hardcode credentials in production code
    
    $gmailUsername = 'eemssoufiane@gmail.com';
    $gmailPassword = 'hmjdcatkbgledfhl'; // App-specific password
    $fromName = 'Cod Intelligence';
    
    Config::set('mail.default', 'smtp');

    Config::set('mail.mailers.smtp', [
        'transport' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => 465,
        'encryption' => 'ssl',
        'username' => $gmailUsername,
        'password' => $gmailPassword,
        'timeout' => 30,
    ]);

    Config::set('mail.from', [
        'address' => $gmailUsername,
        'name' => $fromName,
    ]);

    // IMPORTANT: clear cached mailers so new config is used
    app('mail.manager')->forgetMailers();
}

/**
 * Verify email using token
 */
public function verifyEmail($token)
{
    try {
        // Find user by verification token
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'Invalid verification token'
            ], 404));
        }

        // Check if already verified
        if ($user->isVerified()) {
            return $this->addCorsHeaders(response()->json([
                'success' => true,
                'message' => 'Email already verified'
            ]));
        }

        // Use the built-in method to mark as verified
        $user->markEmailAsVerified();

        // Alternative: Manual update
        // $user->verified = true;
        // $user->email_verified_at = now();
        // $user->verification_token = null;
        // $user->save();

        Log::info('Email verified successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'verified_at' => $user->email_verified_at
        ]);

        return $this->addCorsHeaders(response()->json([
            'success' => true,
            'message' => 'Email verified successfully!',
            'data' => [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'verified_at' => $user->email_verified_at
            ]
        ]));

    } catch (\Exception $e) {
        Log::error('Email verification failed', [
            'token' => $token,
            'error' => $e->getMessage()
        ]);

        return $this->addCorsHeaders(response()->json([
            'success' => false,
            'message' => 'Verification failed. Please try again.',
            'error' => $e->getMessage()
        ], 500));
    }
}
    /**
     * Send verification email
     */
 public function sendVerificationEmail(Request $request)
{
    $this->configureGmail(); // Ensure Gmail SMTP is used

    // Validate input
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return $this->addCorsHeaders(response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404));
    }

    // Generate verification token
    $user->verification_token = Str::random(64);
    $user->save();

    $verificationUrl = url('/verify-email/' . $user->verification_token);

    try {
        // Force the smtp mailer explicitly (avoids cached mailer issues)
        Mail::mailer('smtp')
            ->to($user->email)
            ->send(new VerificationMail($user->name, $verificationUrl));

        // Good debug logs
        Log::info('Verification email SENT', [
            'to' => $user->email,
            'user_id' => $user->id,
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
        ]);

        return $this->addCorsHeaders(response()->json([
            'success' => true,
            'message' => 'Verification email sent'
        ]));

    } catch (\Throwable $e) {
        Log::error('MAIL ERROR', [
            'to' => $user->email,
            'user_id' => $user->id,
            'message' => $e->getMessage(),
            'class' => get_class($e),
        ]);

        return $this->addCorsHeaders(response()->json([
            'success' => false,
            'message' => 'Mail failed',
            'error' => $e->getMessage(),
        ], 500));
    }
}



    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $this->configureGmail(); // Use Gmail

        $email = $request->input('email');
        if (!$email) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'Email is required'
            ], 400));
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404));
        }

        if ($user->verified) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'Email already verified'
            ], 400));
        }

        $user->verification_token = Str::random(64);
        $user->save();

        $verificationUrl = url('/verify-email/' . $user->verification_token);

        try {
            Mail::send('emails.verify', [
                'user' => $user,
                'verificationUrl' => $verificationUrl,
                'appName' => 'Cod Intelligence'
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Verify Your Email - Cod Intelligence');
            });

            return $this->addCorsHeaders(response()->json([
                'success' => true,
                'message' => 'Verification email resent to ' . $user->email
            ]));

        } catch (\Exception $e) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'Failed to resend email',
                'error' => $e->getMessage()
            ], 500));
        }
    }

    /**
     * Test Gmail email
     */
    public function testEmail()
    {
        $this->configureGmail(); // Use Gmail

        try {
            Mail::raw('This is a test email from Cod Intelligence (via Gmail)', function ($message) {
                $message->to('eemssoufiane@gmail.com')
                        ->subject('Test Email - Cod Intelligence');
            });

            return $this->addCorsHeaders(response()->json([
                'success' => true,
                'message' => 'Test email sent successfully!'
            ]));

        } catch (\Exception $e) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500));
        }
    }
}
