{{-- resources/views/emails/admin/new-document.blade.php --}}
<x-mail::message>
# Dokumen Baru Telah Diunggah

Sebuah dokumen baru telah diunggah ke sistem dan menunggu verifikasi Anda.

**Detail Dokumen:**
- **Nama File:** {{ $document->original_filename }}
- **Kode Verifikasi:** {{ $document->verification_code }}
- **IP Pengunggah:** {{ $document->ip_address }}
- **Waktu Unggah:** {{ $document->created_at->format('d M Y, H:i') }}

Silakan klik tombol di bawah ini untuk meninjau dokumen di panel admin.

<x-mail::button :url="$adminUrl">
    Tinjau Dokumen
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>