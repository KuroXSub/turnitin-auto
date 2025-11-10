<?php

namespace App\Filament\Resources\NoResource\Widgets;

use Filament\Widgets\Widget;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminActionsWidget extends Widget implements HasActions, HasForms
{
    use \Filament\Actions\Concerns\InteractsWithActions;
    use \Filament\Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.resources.no-resource.widgets.admin-actions-widget';

    // Atur agar widget mengambil lebar penuh
    protected int | string | array $columnSpan = 'full';

    /**
     * Tindakan 1: Tombol Reset Kuota Harian
     */
    public function resetQuotaAction(): Action
    {
        return Action::make('resetQuota')
            ->label('Reset Kuota Harian')
            ->icon('heroicon-o-arrow-path')
            ->color('success')
            ->requiresConfirmation() // Meminta konfirmasi admin
            ->modalHeading('Reset Kuota Harian?')
            ->modalDescription('Anda yakin ingin mereset kuota unggah harian kembali ke batas default? (Sesuai .env)')
            ->action(function () {
                try {
                    $quota = config('kurosapa.daily_quota', 75);
                    // Langsung set cache (terhubung ke Redis)
                    Cache::put('daily_quota', $quota);
                    
                    Log::info("Admin action: Kuota harian direset ke {$quota}");

                    Notification::make()
                        ->title('Kuota Berhasil Direset')
                        ->body("Kuota harian telah direset ke {$quota}.")
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Gagal Mereset Kuota')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    /**
     * Tindakan 2: Tombol Buka Blokir IP
     */
    public function unblockIpAction(): Action
    {
        return Action::make('unblockIp')
            ->label('Buka Blokir IP')
            ->icon('heroicon-o-lock-open')
            ->color('warning')
            // Tampilkan form popup
            ->form([
                TextInput::make('ip_address')
                    ->label('Alamat IP yang akan dibuka')
                    ->required()
                    ->ip() // Validasi bahwa input adalah IP
                    ->placeholder('Contoh: 127.0.0.1')
            ])
            ->action(function (array $data) {
                try {
                    $ip = $data['ip_address'];
                    
                    // Hapus kedua kunci cache untuk IP tersebut
                    Cache::forget('ip_block:' . $ip);
                    Cache::forget('ip_success_count:' . $ip);

                    Log::info("Admin action: Blokir IP dibuka untuk {$ip}");

                    Notification::make()
                        ->title('Blokir IP Dibuka')
                        ->body("Alamat IP {$ip} telah dibuka blokirnya.")
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Gagal Membuka Blokir')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
