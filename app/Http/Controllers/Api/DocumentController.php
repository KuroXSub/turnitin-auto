<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Jobs\SendAdminNotificationJob;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx|max:20480',
        ]);

        $file = $request->file('document');
        $ipAddress = $request->ip();
        $token = $request->bearerToken();
        $cacheKey = 'upload_token:' . $token;

        $tokenIp = Cache::get($cacheKey);
        if ($tokenIp !== $ipAddress) {
            Cache::increment('daily_quota');
            Cache::forget($cacheKey);
            return response()->json(['message' => 'IP address tidak cocok.'], 403);
        }

        $originalName = $file->getClientOriginalName();
        $path = $file->store('user_uploads', 'r2');

        $verificationCode = Str::upper(Str::random(10));

        $document = Document::create([
            'ip_address' => $ipAddress,
            'original_filename' => $originalName,
            'file_path' => $path,
            'status' => 'pending',
            'verification_code' => $verificationCode,
            'user_id' => null,
        ]);

        Cache::forget($cacheKey);

        dispatch(new SendAdminNotificationJob($document));

        $ipSuccessKey = 'ip_success_count:' . $ipAddress;
        
        $count = Cache::increment($ipSuccessKey);
        
        if ($count === 1) {
            Cache::put($ipSuccessKey, 1, now()->addHour());
        }
        
        if ($count >= 3) {
            Cache::put('ip_block:' . $ipAddress, true, now()->addHour());
            Cache::forget($ipSuccessKey);
        }

        return response()->json([
            'message' => 'Dokumen berhasil diunggah.',
            'verification_code' => $verificationCode
        ], 201);
    }
}
