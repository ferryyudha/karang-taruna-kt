<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class KeuanganMenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Parent Menu "Keuangan"
        $parent = Menu::create([
            'name'      => 'Keuangan',
            'slug'      => 'keuangan',
            'icon'      => 'bi-wallet2',
            'url'       => '#',
            'order'     => 8,
            'is_active' => true,
        ]);

        // 2. Create Sub-menus
        $submenus = [
            ['name' => 'Dashboard Keuangan', 'slug' => 'keuangan_dashboard',  'icon' => 'bi-speedometer',     'url' => '/admin/keuangan/dashboard',   'order' => 1],
            ['name' => 'Pemasukan',          'slug' => 'keuangan_pemasukan',  'icon' => 'bi-arrow-down-left','url' => '/admin/keuangan/pemasukan',   'order' => 2],
            ['name' => 'Pengeluaran',        'slug' => 'keuangan_pengeluaran', 'icon' => 'bi-arrow-up-right',  'url' => '/admin/keuangan/pengeluaran', 'order' => 3],
            ['name' => 'Kategori',           'slug' => 'keuangan_kategori',   'icon' => 'bi-tags',            'url' => '/admin/keuangan/kategori',    'order' => 4],
            ['name' => 'Laporan',            'slug' => 'keuangan_laporan',    'icon' => 'bi-file-earmark-bar-graph', 'url' => '/admin/keuangan/laporan', 'order' => 5],
            ['name' => 'Kas',                'slug' => 'keuangan_kas',        'icon' => 'bi-cash-coin',       'url' => '/admin/keuangan/kas',         'order' => 6],
        ];

        $createdSubmenuIds = [];
        foreach ($submenus as $sub) {
            $sub['parent_id'] = $parent->id;
            $menu = Menu::create($sub);
            $createdSubmenuIds[] = $menu->id;
        }

        // Attach to Admin role
        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $admin->menus()->attach($parent->id);
            $admin->menus()->attach($createdSubmenuIds);
        }
    }
}
