<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        Log::info('📝 REGISTER REQUEST DATA:', $request->all());
        
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:userss,email',
                'password' => 'required|string|min:8',
                'number' => 'nullable|string',
                'numero' => 'nullable|string',
                'profile_picture' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                Log::error('❌ VALIDATION FAILED:', $validator->errors()->toArray());
                
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'first_error' => $validator->errors()->first()
                ], 422);
            }

            $validatedData = $validator->validated();
            Log::info('✅ VALIDATED DATA:', $validatedData);

            // Use number or numero
            $phoneNumber = $validatedData['number'] ?? $validatedData['numero'] ?? null;
            
            // Create user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'number' => $phoneNumber,
                'pictures' => $validatedData['profile_picture'] ?? null,
                'verification_token' => Str::random(64),
                'verified' => false,
                'email_verified_at' => null,
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('❌ REGISTRATION ERROR:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = 'Registration failed';
            
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                if (str_contains($e->getMessage(), 'userss_email_unique')) {
                    $errorMessage = 'البريد الإلكتروني مسجل مسبقاً';
                } else {
                    $errorMessage = 'بيانات مكررة';
                }
            } elseif (str_contains($e->getMessage(), 'SQLSTATE')) {
                $errorMessage = 'خطأ في قاعدة البيانات';
            }
            
            return response()->json([
                'message' => $errorMessage,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();
        return response()->json(['message' => 'Login successful', 'user' => $user], 200);
    }
    
    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    
    /**
     * Configure Gmail SMTP
     */
    private function configureGmail()
    {
        $gmailUsername = 'eemssoufiane@gmail.com';
        $gmailPassword = 'hmjdcatkbgledfhl';
        $fromName = 'منصة الإبلاغ عن الاحتيال';
        
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => 'tls',
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
     * Send password reset link
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'User with this email does not exist'
            ], 404);
        }
        
        // Generate reset token (valid for 1 hour)
        $resetToken = Str::random(64);
        $user->reset_password_token = $resetToken;
        $user->reset_password_expires = now()->addHours(1);
        $user->save();
        
        // Create reset URL
        $resetUrl = 'https://www.codintelligence.com/reset-password?token=' . $resetToken . '&email=' . urlencode($user->email);
        
        // Send email with reset link
        try {
            $this->configureGmail();
            
            Mail::send('emails.password_reset', [
                'user' => $user,
                'resetUrl' => $resetUrl,
                'appName' => 'منصة الإبلاغ عن الاحتيال'
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('إعادة تعيين كلمة المرور - منصة الإبلاغ عن الاحتيال');
            });
            
            Log::info('✅ Password reset link sent', [
                'to' => $user->email,
                'user_id' => $user->id,
            ]);
            
            return response()->json([
                'message' => 'Password reset link has been sent to your email'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('❌ Failed to send password reset email', [
                'to' => $user->email,
                'message' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Failed to send password reset email',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reset password with token
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:userss,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::where('email', $request->email)
                    ->where('reset_password_token', $request->token)
                    ->where('reset_password_expires', '>', now())
                    ->first();
        
        if (!$user) {
            return response()->json([
                'message' => 'Invalid or expired reset token'
            ], 400);
        }
        
        // Reset password
        $user->password = Hash::make($request->password);
        $user->reset_password_token = null;
        $user->reset_password_expires = null;
        $user->save();
        
        return response()->json([
            'message' => 'Password reset successfully'
        ], 200);
    }
    
    /**
     * Verify reset token
     */
    public function verifyResetToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);
        
        $user = User::where('email', $request->email)
                    ->where('reset_password_token', $request->token)
                    ->where('reset_password_expires', '>', now())
                    ->first();
        
        return response()->json([
            'valid' => $user !== null
        ], 200);
    }

    /**
     * Check if email exists
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        return response()->json([
            'exists' => $user !== null,
            'message' => $user ? 'Email exists' : 'Email not found'
        ], 200);
    }
}