<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PruneOldDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-old-documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus dokumen yang statusnya "checked" dan lebih tua dari 7 hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses pembersihan dokumen lama...');

        $cutoff = now()->subDays(7);

        $documents = Document::where('status', 'checked')
                             ->where('checked_at', '<=', $cutoff)
                             ->get();

        if ($documents->isEmpty()) {
            $this->info('Tidak ada dokumen lama yang perlu dihapus.');
            return 0;
        }

        $this->info("Menemukan {$documents->count()} dokumen untuk dihapus.");

        foreach ($documents as $doc) {
            if ($doc->file_path) {
                Storage::disk('r2')->delete($doc->file_path);
            }
            
            if ($doc->resolved_file_path) {
                Storage::disk('r2')->delete($doc->resolved_file_path);
            }
            
            $doc->delete();
            
            $this->line("Dokumen #{$doc->id} ({$doc->original_filename}) telah dihapus.");
        }

        $this->info('Pembersihan dokumen lama selesai.');
        return 0;
    }
}
