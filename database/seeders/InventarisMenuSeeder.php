<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class InventarisMenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reorder menu yang sudah ada 
        $reorder = [
            'dashboard'   => 1,
            'anggota'     => 2,
            'kegiatan'    => 3,
            'pengumuman'  => 4,
            'dokumentasi' => 5,
            // keuangan parent + submenus di-handle terpisah
            'users'       => 60,
            'roles'       => 61,
        ];

        foreach ($reorder as $slug => $order) {
            Menu::where('slug', $slug)->update(['order' => $order]);
        }

        // Update Keuangan parent → order 6
        $keuangan = Menu::where('slug', 'keuangan')->first();
        if ($keuangan) {
            $keuangan->update(['order' => 6]);
            // Submenus keuangan tetap urutannya (sudah punya order sendiri)
        }

        // Laporan → order 8 (nanti setelah inventaris=7)
        Menu::where('slug', 'laporan')->update(['order' => 8]);

        // Manajemen User & Role gabung jadi satu parent baru
        // Users & Roles tetap order 60,61 di bawah parent manajemen
        // Buat parent "Manajemen" jika belum ada
        $manajemen = Menu::firstOrCreate(
            ['slug' => 'manajemen'],
            [
                'name'      => 'Manajemen User & Role',
                'icon'      => 'bi-person-gear',
                'url'       => '#',
                'order'     => 9,
                'is_active' => true,
            ]
        );
        $manajemen->update(['icon' => 'bi-person-gear', 'order' => 9]);

        // Set parent_id untuk users & roles ke menu manajemen
        Menu::where('slug', 'users')->update(['parent_id' => $manajemen->id]);
        Menu::where('slug', 'roles')->update(['parent_id' => $manajemen->id]);

        // 2. Tambah menu Inventaris/Perlengkapan jika belum ada 
        $inventarisParent = Menu::firstOrCreate(
            ['slug' => 'inventaris'],
            [
                'name'      => 'Inventaris',
                'icon'      => 'bi-box-seam',
                'url'       => '#',
                'order'     => 7,
                'is_active' => true,
            ]
        );
        $inventarisParent->update(['order' => 7]);

        // Sub-menu Inventaris
        $submenus = [
            [
                'name'      => 'Daftar Inventaris',
                'slug'      => 'inventaris-daftar',
                'icon'      => 'bi-list-ul',
                'url'       => '/admin/inventaris',
                'parent_id' => $inventarisParent->id,
                'order'     => 1,
                'is_active' => true,
            ],
            [
                'name'      => 'Kategori Barang',
                'slug'      => 'inventaris-kategori',
                'icon'      => 'bi-tags',
                'url'       => '/admin/inventaris/kategori',
                'parent_id' => $inventarisParent->id,
                'order'     => 2,
                'is_active' => true,
            ],
            [
                'name'      => 'Peminjaman',
                'slug'      => 'inventaris-peminjaman',
                'icon'      => 'bi-arrow-left-right',
                'url'       => '/admin/inventaris/peminjaman',
                'parent_id' => $inventarisParent->id,
                'order'     => 3,
                'is_active' => true,
            ],
        ];

        foreach ($submenus as $sub) {
            Menu::firstOrCreate(['slug' => $sub['slug']], $sub);
        }

        $this->command->info('Menu Inventaris berhasil ditambahkan dan urutan menu diperbarui!');
    }
}
