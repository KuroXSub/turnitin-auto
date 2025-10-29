<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ValidateUploadToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['message' => 'Token tidak ditemukan.'], 401);
        }

        $cacheKey = 'upload_token:' . $token;

        if (!Cache::has($cacheKey)) {
            return response()->json(['message' => 'Token tidak valid atau kedaluwarsa.'], 401);
        }

        return $next($request);
    }
}
