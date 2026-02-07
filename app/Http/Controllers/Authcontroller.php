<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class Authcontroller extends Controller
{

       protected function logout(Request $request){
        auth()->logout();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    //
public function register(Request $request){
    \Log::info('📝 REGISTER REQUEST DATA:', $request->all());
    
    try {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:userss', // Changed from 'users' to 'userss'
            'password' => 'required|string|min:8',
            'number' => 'nullable|string|unique:userss',
            'numero' => 'nullable|string|unique:userss',
            'profile_picture' => 'nullable|string', // Just accept URL string
        ]);

        \Log::info('✅ VALIDATED DATA:', $validatedData);

        // Use number or numero
        $phoneNumber = $validatedData['number'] ?? $validatedData['numero'] ?? 'default_' . time();
        
        // Hash password
        $validatedData['password'] = bcrypt($validatedData['password']);
        
        // Prepare user data - just store whatever URL is sent
        $userData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'number' => $phoneNumber,
            'pictures' => $validatedData['profile_picture'] ?? null, // Store the URL
            'verification_token' => \Illuminate\Support\Str::random(64),
        ];
        
        $user = User::create($userData);

        return response()->json([
            'message' => 'User registered successfully', 
            'user' => $user
        ], 201);
        
    } catch (\Exception $e) {
        \Log::error('❌ REGISTRATION ERROR:', ['message' => $e->getMessage()]);
        
        return response()->json([
            'message' => 'Registration failed',
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function login(Request $request){
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
    
}
