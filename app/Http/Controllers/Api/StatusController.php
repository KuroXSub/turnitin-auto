<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class StatusController extends Controller
{
    public function checkStatus(string $verification_code)
    {
        $document = Document::where('verification_code', $verification_code)->first();

        if (!$document) {
            return response()->json(['message' => 'Kode verifikasi tidak ditemukan.'], 404);
        }

        $resultUrl = null;

        if ($document->status === 'checked' && $document->resolved_file_path) {
            
            try {
                $resultUrl = Storage::disk('r2')->temporaryUrl(
                    $document->resolved_file_path,
                    now()->addHour()
                );
            } catch (\Exception $e) {
                $resultUrl = null; 
            }
        }

        return response()->json([
            'status' => $document->status,
            'verification_code' => $document->verification_code,
            'original_filename' => $document->original_filename,
            'admin_notes' => $document->admin_notes,
            'checked_at' => $document->checked_at,
            'result_download_url' => $resultUrl
        ]);
    }
}
