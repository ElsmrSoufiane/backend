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
        return $response->header('Access-Control-Allow-Origin', 'https://www.codintelligence.com')
                       ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                       ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                       ->header('Access-Control-Allow-Credentials', 'true');
    }
    
    public function checkVerification(Request $request)
    {
        $this->configureGmail();

        $email = $request->input('email');

        if (!$email) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني مطلوب'
            ], 400));
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
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
        $gmailUsername = 'eemssoufiane@gmail.com';
        $gmailPassword = 'hmjdcatkbgledfhl';
        $fromName = 'منصة الإبلاغ عن الاحتيال';
        
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

        app('mail.manager')->forgetMailers();
    }

    /**
     * Verify email using token
     */
    public function verifyEmail($token)
    {
        try {
            $user = User::where('verification_token', $token)->first();

            if (!$user) {
                return redirect('https://www.codintelligence.com');
            }

            if (!$user->verified) {
                $user->verified = true;
                $user->email_verified_at = now();
                $user->verification_token = null;
                $user->save();
            }

            return redirect('https://www.codintelligence.com/verify-email/success');

        } catch (\Exception $e) {
            return redirect('https://www.codintelligence.com');
        }
    }
    
    /**
     * Send verification email
     */
    public function sendVerificationEmail(Request $request)
    {
        $this->configureGmail();

        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
            ], 404));
        }

        $user->verification_token = Str::random(64);
        $user->save();

        $verificationUrl = url('/verify-email/' . $user->verification_token);

        try {
            // Send Arabic email template
            Mail::send('emails.verify', [
                'user' => $user,
                'verificationUrl' => $verificationUrl,
                'appName' => 'منصة الإبلاغ عن الاحتيال'
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('توثيق البريد الإلكتروني - منصة الإبلاغ عن الاحتيال');
            });

            Log::info('تم إرسال بريد التوثيق', [
                'to' => $user->email,
                'user_id' => $user->id,
            ]);

            return $this->addCorsHeaders(response()->json([
                'success' => true,
                'message' => 'تم إرسال بريد التوثيق بنجاح'
            ]));

        } catch (\Throwable $e) {
            Log::error('خطأ في إرسال البريد', [
                'to' => $user->email,
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'فشل إرسال البريد',
                'error' => $e->getMessage(),
            ], 500));
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $this->configureGmail();

        $email = $request->input('email');
        if (!$email) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني مطلوب'
            ], 400));
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'المستخدم غير موجود'
            ], 404));
        }

        if ($user->verified) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني موثق بالفعل'
            ], 400));
        }

        $user->verification_token = Str::random(64);
        $user->save();

        $verificationUrl = url('/verify-email/' . $user->verification_token);

        try {
            Mail::send('emails.verify-arabic', [
                'user' => $user,
                'verificationUrl' => $verificationUrl,
                'appName' => 'منصة الإبلاغ عن الاحتيال'
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('إعادة إرسال بريد التوثيق - منصة الإبلاغ عن الاحتيال');
            });

            return $this->addCorsHeaders(response()->json([
                'success' => true,
                'message' => 'تم إعادة إرسال بريد التوثيق إلى ' . $user->email
            ]));

        } catch (\Exception $e) {
            return $this->addCorsHeaders(response()->json([
                'success' => false,
                'message' => 'فشل إعادة إرسال البريد',
                'error' => $e->getMessage()
            ], 500));
        }
    }
}