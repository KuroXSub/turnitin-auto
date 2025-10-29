<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-g">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Evaluasi Dokumen' }}</title>
        @vite('resources/css/app.css')
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-100">
        {{-- Navigasi bisa ditaruh di sini --}}
        <main>
            {{ $slot }}
        </main>
        @livewireScripts
    </body>
</html>