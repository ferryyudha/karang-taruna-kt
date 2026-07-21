<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PollingMenuSeeder extends Seeder
{
    public function run(): void
    {
        $admin    = Role::where('slug', 'admin')->first();
        $pengurus = Role::where('slug', 'pengurus')->first();

        // Menu Polling Admin (CRUD + hasil)
        $menuPolling = Menu::firstOrCreate(
            ['slug' => 'polling'],
            [
                'name'  => 'Polling',
                'icon'  => 'bi-bar-chart-fill',
                'url'   => '/admin/polling',
                'order' => 20,
            ]
        );

        // Menu Polling Anggota (vote & lihat hasil)
        $menuPollingAnggota = Menu::firstOrCreate(
            ['slug' => 'anggota-polling'],
            [
                'name'  => 'Polling Anggota',
                'icon'  => 'bi-hand-index-thumb',
                'url'   => '/admin/anggota-area/polling',
                'order' => 21,
            ]
        );

        // Admin mendapat kedua menu
        if ($admin) {
            $admin->menus()->syncWithoutDetaching([
                $menuPolling->id,
                $menuPollingAnggota->id,
            ]);
        }

        // Pengurus mendapat menu polling admin
        if ($pengurus) {
            $pengurus->menus()->syncWithoutDetaching([
                $menuPolling->id,
                $menuPollingAnggota->id,
            ]);
        }

        $this->command->info('✓ Menu Polling berhasil ditambahkan ke tabel menus.');
    }
}
