<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function requestToken(Request $request)
    {
        Log::info('Menerima request token dari IP: ' . $request->ip());

        $ipBlockKey = 'ip_block:' . $request->ip();
        if (Cache::has($ipBlockKey)) {
            Log::warning('IP ' . $request->ip() . ' diblokir.');
            return response()->json(['message' => 'Anda telah mencapai batas 3x pengiriman per jam. Silakan coba lagi nanti.'], 429);
        }

        $quota = (int) Cache::get('daily_quota', 0);
        Log::info('Mengecek kuota saat ini: ' . $quota);

        if ($quota <= 0) {
            Log::warning('Kuota harian penuh (sisa: ' . $quota . '). Request token ditolak.');
            return response()->json(['message' => 'Kuota unggah harian penuh. Silakan coba lagi besok.'], 429);
        }

        $token = Str::random(60);
        $cacheKey = 'upload_token:' . $token;
        Cache::put($cacheKey, $request->ip(), now()->addMinutes(5));

        Log::info('Token dibuat untuk IP: ' . $request->ip());

        return response()->json([
            'message' => 'Token berhasil dibuat.',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 300 
        ]);
    }
}
