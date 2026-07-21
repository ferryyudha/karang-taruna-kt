<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        $anggota = [
            ['nama' => 'Ahmad Fauzi',      'jabatan' => 'Ketua',               'urutan' => 1,  'periode' => '2023-2025'],
            ['nama' => 'Siti Rahayu',      'jabatan' => 'Wakil Ketua',         'urutan' => 2,  'periode' => '2023-2025'],
            ['nama' => 'Budi Santoso',     'jabatan' => 'Sekretaris',          'urutan' => 3,  'periode' => '2023-2025'],
            ['nama' => 'Dewi Lestari',     'jabatan' => 'Wakil Sekretaris',    'urutan' => 4,  'periode' => '2023-2025'],
            ['nama' => 'Eko Prasetyo',     'jabatan' => 'Bendahara',           'urutan' => 5,  'periode' => '2023-2025'],
            ['nama' => 'Fitriani Putri',   'jabatan' => 'Wakil Bendahara',     'urutan' => 6,  'periode' => '2023-2025'],
            ['nama' => 'Galih Permana',    'jabatan' => 'Bidang Sosial',       'urutan' => 7,  'periode' => '2023-2025'],
            ['nama' => 'Heni Susanti',     'jabatan' => 'Bidang Pendidikan',   'urutan' => 8,  'periode' => '2023-2025'],
            ['nama' => 'Irfan Maulana',    'jabatan' => 'Bidang Olahraga',     'urutan' => 9,  'periode' => '2023-2025'],
            ['nama' => 'Julia Maharani',   'jabatan' => 'Bidang Seni Budaya',  'urutan' => 10, 'periode' => '2023-2025'],
        ];

        foreach ($anggota as $a) {
            Anggota::create(array_merge($a, ['aktif' => true]));
        }
    }
}
