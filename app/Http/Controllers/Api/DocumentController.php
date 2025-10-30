<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Jobs\SendAdminNotificationJob;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx|max:20480',
        ]);

        $ipAddress = $request->ip();
        Log::info("Menerima unggahan file dari IP: " . $ipAddress);

        $quota = Cache::decrement('daily_quota');
        
        if ($quota < 0) {
            Cache::increment('daily_quota');
            Log::warning('Upload gagal: Kuota habis saat mencoba mengunggah. IP: ' . $ipAddress);
            
            Cache::forget('upload_token:' . $request->bearerToken());
            
            return response()->json(['message' => 'Upload gagal, kuota harian sudah habis.'], 429);
        }
        
        Log::info('Kuota berhasil diambil. Sisa kuota: ' . $quota);

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        
        $path = $file->store('user_uploads', 's3');
        Log::info('File disimpan di: ' . $path);

        $verificationCode = Str::upper(Str::random(10));

        $document = Document::create([
            'ip_address' => $ipAddress,
            'original_filename' => $originalName,
            'file_path' => $path,
            'status' => 'pending',
            'verification_code' => $verificationCode,
            'user_id' => null,
        ]);

        Cache::forget('upload_token:' . $request->bearerToken());

        dispatch(new SendAdminNotificationJob($document));
        Log::info('Notifikasi admin dikirim ke queue.');

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
