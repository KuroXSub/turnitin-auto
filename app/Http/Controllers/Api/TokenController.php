<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function requestToken(Request $request)
    {
        $ipBlockKey = 'ip_block:' . $request->ip();
        if (Cache::has($ipBlockKey)) {
            return response()->json(['message' => 'Anda telah mencapai batas 3x pengiriman per jam. Silakan coba lagi nanti.'], 429);
        }

        $quota = Cache::decrement('daily_quota');

        if ($quota < 0) {
            Cache::increment('daily_quota'); 
            return response()->json(['message' => 'Kuota unggah harian penuh. Silakan coba lagi besok.'], 429);
        }

        $token = Str::random(60);
        $cacheKey = 'upload_token:' . $token;

        Cache::put($cacheKey, $request->ip(), now()->addMinutes(5));

        return response()->json([
            'message' => 'Token berhasil dibuat.',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 300
        ]);
    }
}
