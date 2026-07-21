<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\KeuanganKategori;
use Illuminate\Database\Seeder;

class KeuanganIuranMenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan kategori "Iuran Anggota" (Pemasukan) tersedia
        KeuanganKategori::firstOrCreate(
            ['nama' => 'Iuran Anggota', 'tipe' => 'pemasukan']
        );

        // 2. Cari parent menu Keuangan
        $parent = Menu::where('slug', 'keuangan')->first();
        if (!$parent) {
            $parent = Menu::create([
                'name'      => 'Keuangan',
                'slug'      => 'keuangan',
                'icon'      => 'bi-wallet2',
                'url'       => '#',
                'order'     => 8,
                'is_active' => true,
            ]);
        }

        // 3. Buat sub-menu Iuran Anggota jika belum ada
        $menuIuran = Menu::where('slug', 'keuangan_iuran')->first();
        if (!$menuIuran) {
            $menuIuran = Menu::create([
                'parent_id' => $parent->id,
                'name'      => 'Iuran Anggota',
                'slug'      => 'keuangan_iuran',
                'icon'      => 'bi-receipt',
                'url'       => '/admin/keuangan/iuran',
                'order'     => 7,
                'is_active' => true,
            ]);
        }

        // 4. Attach ke role Admin
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            if (!$admin->menus()->where('menu_id', $parent->id)->exists()) {
                $admin->menus()->attach($parent->id);
            }
            if (!$admin->menus()->where('menu_id', $menuIuran->id)->exists()) {
                $admin->menus()->attach($menuIuran->id);
            }
        }
    }
}
