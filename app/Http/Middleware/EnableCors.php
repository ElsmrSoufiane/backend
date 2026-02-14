<?php
// app/Http/Middleware/EnableCors.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnableCors
{
    public function handle(Request $request, Closure $next)
    {
        // Log for debugging
        \Log::info('EnableCors middleware triggered for: ' . $request->url());
        \Log::info('Origin: ' . $request->header('Origin'));
        
        // Handle preflight requests
        if ($request->isMethod('OPTIONS')) {
            \Log::info('Handling OPTIONS preflight');
            return response('', 200)
                ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }

        $response = $next($request);
        
        // Log headers being set
        \Log::info('Setting CORS headers for response');
        
        // Add CORS headers
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
        
        return $response;
    }
}
