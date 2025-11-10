{{-- resources/views/filament/widgets/admin-actions-widget.blade.php --}}
<x-filament-widgets::widget>
    
    {{-- 'heading' sekarang menjadi atribut, bukan komponen 'x-filament::card.heading' --}}
    <x-filament::card heading="Tindakan Cepat Server">

        <p class="text-sm text-gray-500">
            Gunakan tombol ini untuk mengelola cache dan kuota aplikasi.
        </p>

        <div class="mt-4 flex flex-wrap gap-2">
            {{-- Ini memanggil action 'resetQuotaAction' dari file PHP --}}
            {{ $this->resetQuotaAction }}

            {{-- Ini memanggil action 'unblockIpAction' dari file PHP --}}
            {{ $this->unblockIpAction }}
        </div>

    </x-filament::card>
</x-filament-widgets::widget>