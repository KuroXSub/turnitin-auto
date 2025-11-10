<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ResetDailyQuota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-daily-quota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset kuota unggah harian kembali ke batas awal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $quota = config('kurosapa.daily_quota', 50);

        Cache::put('daily_quota', $quota);

        $this->info("Kuota harian berhasil di-reset ke: {$quota}");
        return 0;
    }
}
