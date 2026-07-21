<?php

namespace App\Console\Commands;

use App\Models\Polling;
use Illuminate\Console\Command;

class UpdatePollingStatus extends Command
{
    protected $signature   = 'polling:update-status';
    protected $description = 'Otomatis ubah status polling aktif menjadi selesai jika sudah lewat selesai_at';

    public function handle(): void
    {
        $updated = Polling::where('status', 'aktif')
            ->where('selesai_at', '<', now())
            ->update(['status' => 'selesai']);

        $this->info("✓ {$updated} polling diperbarui ke status 'selesai'.");
    }
}
