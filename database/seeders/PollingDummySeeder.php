<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Polling;
use App\Models\PollingOpsi;
use App\Models\PollingVote;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PollingDummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Dapatkan atau buat Role Pengurus (agar user dummy memiliki role valid)
        $pengurusRole = Role::where('slug', 'pengurus')->first();
        if (!$pengurusRole) {
            $pengurusRole = Role::create([
                'name' => 'Pengurus',
                'slug' => 'pengurus',
                'description' => 'Pengurus umum Karang Taruna'
            ]);
        }

        // Dapatkan user admin yang ada untuk pencipta polling
        $adminUser = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->first() 
            ?? User::first();

        if (!$adminUser) {
            // Jika benar-benar kosong, buat admin sementara
            $adminUser = User::create([
                'name' => 'Admin Utama',
                'email' => 'admin.polling@mail.com',
                'password' => Hash::make('password123'),
                'phone' => '081299998888',
                'is_active' => true,
            ]);
        }

        // 2. Buat 10 User Dummy untuk menyumbang Vote agar grafik langsung penuh & hidup
        $voters = [];
        for ($i = 1; $i <= 12; $i++) {
            $email = "voter.dummy{$i}@mail.com";
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => "Anggota Tester {$i}",
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role_id' => $pengurusRole->id,
                    'phone' => '08999988877' . $i,
                    'is_active' => true,
                ]);
            }
            $voters[] = $user;
        }

        // Hapus polling dummy lama agar tidak menumpuk saat dijalankan ulang
        Polling::whereIn('judul', [
            'Pilih Tema Kegiatan HUT RI Ke-81',
            'Hari Latihan Rutin Futsal Mingguan',
            'Desain Seragam Karang Taruna Baru',
            'Materi Workshop IT Pemuda'
        ])->delete();

        // 3. POLLING 1: Selesai, Tampil Publik (Pilih Tema HUT RI)
        $poll1 = Polling::create([
            'judul' => 'Pilih Tema Kegiatan HUT RI Ke-81',
            'deskripsi' => 'Rapat menentukan tema besar perayaan Hari Kemerdekaan RI tingkat RW/Kelurahan.',
            'tipe' => 'single',
            'mulai_at' => Carbon::now()->subDays(10),
            'selesai_at' => Carbon::now()->subDays(1),
            'status' => 'selesai',
            'tampil_publik' => true,
            'dibuat_oleh' => $adminUser->id,
        ]);

        $opsi1_1 = PollingOpsi::create(['polling_id' => $poll1->id, 'teks_opsi' => 'Kemerdekaan Hijau (Zero Waste)', 'urutan' => 1]);
        $opsi1_2 = PollingOpsi::create(['polling_id' => $poll1->id, 'teks_opsi' => 'Tradisi Nusantara & Budaya Lokal', 'urutan' => 2]);
        $opsi1_3 = PollingOpsi::create(['polling_id' => $poll1->id, 'teks_opsi' => 'Generasi Digital & Teknologi Modern', 'urutan' => 3]);

        // Distribusikan vote (12 voter + admin)
        // Opsi 1 dapat 6 suara, Opsi 2 dapat 5 suara, Opsi 3 dapat 2 suara
        $voterIdx = 0;
        // Opsi 1 (6 suara)
        for ($j = 0; $j < 6; $j++) {
            PollingVote::create(['polling_id' => $poll1->id, 'polling_opsi_id' => $opsi1_1->id, 'user_id' => $voters[$voterIdx++]->id]);
        }
        // Opsi 2 (5 suara)
        for ($j = 0; $j < 5; $j++) {
            PollingVote::create(['polling_id' => $poll1->id, 'polling_opsi_id' => $opsi1_2->id, 'user_id' => $voters[$voterIdx++]->id]);
        }
        // Opsi 3 (2 suara)
        PollingVote::create(['polling_id' => $poll1->id, 'polling_opsi_id' => $opsi1_3->id, 'user_id' => $voters[$voterIdx++]->id]); // voter 12
        PollingVote::create(['polling_id' => $poll1->id, 'polling_opsi_id' => $opsi1_3->id, 'user_id' => $adminUser->id]);


        // 4. POLLING 2: Aktif, Tidak Tampil Publik (Jadwal Futsal)
        $poll2 = Polling::create([
            'judul' => 'Hari Latihan Rutin Futsal Mingguan',
            'deskripsi' => 'Menentukan jadwal sewa lapangan rutin mingguan untuk tim olahraga Karang Taruna.',
            'tipe' => 'single',
            'mulai_at' => Carbon::now()->subDays(2),
            'selesai_at' => Carbon::now()->addDays(5),
            'status' => 'aktif',
            'tampil_publik' => false,
            'dibuat_oleh' => $adminUser->id,
        ]);

        $opsi2_1 = PollingOpsi::create(['polling_id' => $poll2->id, 'teks_opsi' => 'Sabtu Sore (16:00 - 18:00)', 'urutan' => 1]);
        $opsi2_2 = PollingOpsi::create(['polling_id' => $poll2->id, 'teks_opsi' => 'Minggu Pagi (07:00 - 09:00)', 'urutan' => 2]);
        $opsi2_3 = PollingOpsi::create(['polling_id' => $poll2->id, 'teks_opsi' => 'Jumat Malam (19:00 - 21:00)', 'urutan' => 3]);

        // Berikan beberapa vote masuk (misal baru 6 dari 12 user yang vote)
        for ($j = 0; $j < 3; $j++) {
            PollingVote::create(['polling_id' => $poll2->id, 'polling_opsi_id' => $opsi2_1->id, 'user_id' => $voters[$j]->id]);
        }
        for ($j = 3; $j < 5; $j++) {
            PollingVote::create(['polling_id' => $poll2->id, 'polling_opsi_id' => $opsi2_2->id, 'user_id' => $voters[$j]->id]);
        }
        PollingVote::create(['polling_id' => $poll2->id, 'polling_opsi_id' => $opsi2_3->id, 'user_id' => $voters[5]->id]);


        // 5. POLLING 3: Aktif, Tampil Publik, Multi Pilihan (Desain Seragam Baru)
        $poll3 = Polling::create([
            'judul' => 'Desain Seragam Karang Taruna Baru',
            'deskripsi' => 'Pilih salah satu atau lebih desain yang paling kamu sukai untuk seragam resmi periode baru.',
            'tipe' => 'multi',
            'mulai_at' => Carbon::now()->subDays(1),
            'selesai_at' => Carbon::now()->addDays(8),
            'status' => 'aktif',
            'tampil_publik' => true,
            'dibuat_oleh' => $adminUser->id,
        ]);

        $opsi3_1 = PollingOpsi::create(['polling_id' => $poll3->id, 'teks_opsi' => 'Desain A - Jaket Bomber Navy Blue', 'urutan' => 1]);
        $opsi3_2 = PollingOpsi::create(['polling_id' => $poll3->id, 'teks_opsi' => 'Desain B - Polo Shirt Sporty Putih-Merah', 'urutan' => 2]);
        $opsi3_3 = PollingOpsi::create(['polling_id' => $poll3->id, 'teks_opsi' => 'Desain C - Kemeja PDL Hitam List Gold', 'urutan' => 3]);

        // Simulasikan multi voting (beberapa user memilih lebih dari 1 opsi)
        // User 0 memilih opsi A & C
        PollingVote::create(['polling_id' => $poll3->id, 'polling_opsi_id' => $opsi3_1->id, 'user_id' => $voters[0]->id]);
        PollingVote::create(['polling_id' => $poll3->id, 'polling_opsi_id' => $opsi3_3->id, 'user_id' => $voters[0]->id]);

        // User 1 memilih opsi B & C
        PollingVote::create(['polling_id' => $poll3->id, 'polling_opsi_id' => $opsi3_2->id, 'user_id' => $voters[1]->id]);
        PollingVote::create(['polling_id' => $poll3->id, 'polling_opsi_id' => $opsi3_3->id, 'user_id' => $voters[1]->id]);

        // User 2 & 3 hanya memilih C
        PollingVote::create(['polling_id' => $poll3->id, 'polling_opsi_id' => $opsi3_3->id, 'user_id' => $voters[2]->id]);
        PollingVote::create(['polling_id' => $poll3->id, 'polling_opsi_id' => $opsi3_3->id, 'user_id' => $voters[3]->id]);

        // User 4 hanya memilih A
        PollingVote::create(['polling_id' => $poll3->id, 'polling_opsi_id' => $opsi3_1->id, 'user_id' => $voters[4]->id]);


        // 6. POLLING 4: Draft (Materi Workshop IT Pemuda)
        $poll4 = Polling::create([
            'judul' => 'Materi Workshop IT Pemuda',
            'deskripsi' => 'Polling untuk menentukan pelatihan IT yang paling dibutuhkan oleh remaja di kelurahan.',
            'tipe' => 'single',
            'mulai_at' => Carbon::now()->addDays(2),
            'selesai_at' => Carbon::now()->addDays(9),
            'status' => 'draft',
            'tampil_publik' => true,
            'dibuat_oleh' => $adminUser->id,
        ]);

        PollingOpsi::create(['polling_id' => $poll4->id, 'teks_opsi' => 'Pengembangan Website Laravel 11', 'urutan' => 1]);
        PollingOpsi::create(['polling_id' => $poll4->id, 'teks_opsi' => 'Desain Grafis dengan Canva & Figma', 'urutan' => 2]);
        PollingOpsi::create(['polling_id' => $poll4->id, 'teks_opsi' => 'Digital Marketing & Copywriting Praktis', 'urutan' => 3]);
    }
}
