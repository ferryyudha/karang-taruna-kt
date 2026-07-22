<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Anggota;
use App\Models\Pengumuman;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
use App\Models\Lomba;
use App\Models\Polling;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── 0. Pastikan Seeder Menu & User Utama Sudah Berjalan ────────────────
        $this->call([
            RoleMenuSeeder::class,
            UserSeeder::class,
            AnggotaSeeder::class,
            InventarisMenuSeeder::class,
            KeuanganMenuSeeder::class,
            LombaMenuSeeder::class,
            PengaduanMenuSeeder::class,
            PollingMenuSeeder::class,
        ]);

        $adminUser = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->first() ?? User::first();
        if (!$adminUser) {
            $this->command->error('Gagal menjalankan dummy seeder: User admin tidak ditemukan.');
            return;
        }

        // ── 1. Dummy Pengumuman ───────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pengumuman')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Pengumuman::create([
            'judul' => 'Agenda Kerja Bakti Bersama Warga RT 01-05',
            'isi' => 'Diberitahukan kepada seluruh pemuda Karang Taruna dan warga untuk berkumpul di lapangan utama hari Minggu besok pukul 07:00 WIB guna melaksanakan bersih-bersih drainase lingkungan.',
            'kategori' => 'Sosial',
            'tanggal' => Carbon::now()->addDays(2)->toDateString(),
            'status' => 'publish',
            'user_id' => $adminUser->id,
        ]);

        Pengumuman::create([
            'judul' => 'Undangan Rapat Pleno Koordinasi HUT RI',
            'isi' => 'Diharapkan kehadiran pengurus inti dan koordinasi divisi dalam rapat pleno pembentukan panitia pelaksana HUT RI ke-80 pada Sabtu malam di Balai RW.',
            'kategori' => 'Rapat',
            'tanggal' => Carbon::now()->subDays(1)->toDateString(),
            'status' => 'publish',
            'user_id' => $adminUser->id,
        ]);

        Pengumuman::create([
            'judul' => 'Pemberitahuan Iuran Kas Anggota Periode Juli',
            'isi' => 'Mengingatkan kembali kepada rekan-rekan anggota untuk melunasi iuran wajib bulanan sebesar Rp 15.000 melalui bendahara atau transfer ke rekening kas sosial.',
            'kategori' => 'Informasi',
            'tanggal' => Carbon::now()->subDays(5)->toDateString(),
            'status' => 'publish',
            'user_id' => $adminUser->id,
        ]);

        // ── 2. Dummy Kegiatan & Dokumentasi ───────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kegiatan')->truncate();
        DB::table('dokumentasi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $keg1 = Kegiatan::create([
            'nama' => 'Turnamen Futsal Persahabatan Pemuda',
            'deskripsi' => 'Ajang kompetisi olahraga antar RT untuk menjalin tali silaturahmi pemuda Karang Taruna.',
            'tanggal' => Carbon::now()->subDays(4)->toDateString(),
            'lokasi' => 'Gedung Olahraga (GOR) Kecamatan',
            'status' => 'completed',
            'foto_cover' => null,
            'user_id' => $adminUser->id,
        ]);

        Dokumentasi::create([
            'kegiatan_id' => $keg1->id,
            'foto' => 'sample_futsal_1.jpg',
            'keterangan' => 'Foto bersama seluruh tim peserta turnamen futsal.',
        ]);
        Dokumentasi::create([
            'kegiatan_id' => $keg1->id,
            'foto' => 'sample_futsal_2.jpg',
            'keterangan' => 'Penyerahan piala oleh ketua Karang Taruna kepada juara 1.',
        ]);

        $keg2 = Kegiatan::create([
            'nama' => 'Bakti Sosial & Pembagian Sembako Murah',
            'deskripsi' => 'Penyaluran paket kebutuhan pokok bersubsidi kepada keluarga prasejahtera di lingkungan sekitar.',
            'tanggal' => Carbon::now()->addDays(5)->toDateString(),
            'lokasi' => 'Balai Pertemuan RW',
            'status' => 'upcoming',
            'foto_cover' => null,
            'user_id' => $adminUser->id,
        ]);

        $keg3 = Kegiatan::create([
            'nama' => 'Kerja Bakti Massal & Penghijauan Kampung',
            'deskripsi' => 'Kegiatan gotong royong membersihkan saluran air dan menanam pohon produktif di sepanjang jalan utama.',
            'tanggal' => Carbon::now()->toDateString(),
            'lokasi' => 'Jalan Utama Protokol Kelurahan',
            'status' => 'ongoing',
            'foto_cover' => null,
            'user_id' => $adminUser->id,
        ]);


        // ── 3. Dummy Lomba (Berelasi dengan Kegiatan) ─────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lomba')->truncate();
        DB::table('lomba_peralatan')->truncate();
        DB::table('lomba_peserta')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $lomba1 = Lomba::create([
            'kegiatan_id' => $keg1->id, // Futsal
            'nama' => 'Lomba Futsal U-19',
            'kategori' => 'Remaja',
            'deskripsi' => 'Kompetisi futsal kelompok umur maksimal 19 tahun.',
            'tanggal' => $keg1->tanggal,
            'waktu_mulai' => '08:00:00',
            'lokasi' => $keg1->lokasi,
            'penanggung_jawab' => 'Irfan Maulana (Koor Olahraga)',
            'status' => 'selesai',
            'user_id' => $adminUser->id,
        ]);

        DB::table('lomba_peserta')->insert([
            ['lomba_id' => $lomba1->id, 'nama_peserta' => 'Tim RT 01 FC', 'nomor_urut' => 'A1', 'kategori_usia' => 'Remaja', 'kontak' => '08123444', 'juara' => 'Juara 2', 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => $lomba1->id, 'nama_peserta' => 'Tim RT 02 United', 'nomor_urut' => 'A2', 'kategori_usia' => 'Remaja', 'kontak' => '08123555', 'juara' => 'Juara 1', 'created_at' => now(), 'updated_at' => now()],
            ['lomba_id' => $lomba1->id, 'nama_peserta' => 'Tim RT 03 Stars', 'nomor_urut' => 'A3', 'kategori_usia' => 'Remaja', 'kontak' => '08123666', 'juara' => 'Juara 3', 'created_at' => now(), 'updated_at' => now()],
        ]);


        // ── 4. Dummy Keuangan (Kas, Kategori, Transaksi) ─────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('keuangan_kas')->truncate();
        DB::table('keuangan_kategori')->truncate();
        DB::table('keuangan_transaksi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $kasUtama = DB::table('keuangan_kas')->insertGetId([
            'nama' => 'Kas Utama Karang Taruna',
            'keterangan' => 'Kas utama untuk segala jenis pemasukan dan operasional rutin.',
            'saldo' => 4500000.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $kasSosial = DB::table('keuangan_kas')->insertGetId([
            'nama' => 'Kas Sosial & Peduli',
            'keterangan' => 'Kas khusus sumbangan, dana sosial, dan bantuan kebencanaan.',
            'saldo' => 1250000.00,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $katPem1 = DB::table('keuangan_kategori')->insertGetId(['nama' => 'Uang Kas / Iuran Anggota', 'tipe' => 'pemasukan', 'created_at' => now(), 'updated_at' => now()]);
        $katPem2 = DB::table('keuangan_kategori')->insertGetId(['nama' => 'Sponsorship & Donatur', 'tipe' => 'pemasukan', 'created_at' => now(), 'updated_at' => now()]);
        $katPeng1 = DB::table('keuangan_kategori')->insertGetId(['nama' => 'Pembelian Alat / Inventaris', 'tipe' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()]);
        $katPeng2 = DB::table('keuangan_kategori')->insertGetId(['nama' => 'Konsumsi & Rapat', 'tipe' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()]);

        DB::table('keuangan_transaksi')->insert([
            [
                'kas_id' => $kasUtama,
                'kategori_id' => $katPem1,
                'tipe' => 'pemasukan',
                'jumlah' => 750000.00,
                'tanggal' => Carbon::now()->subDays(6)->toDateString(),
                'keterangan' => 'Kolektif kas wajib bulanan dari pengurus & anggota.',
                'user_id' => $adminUser->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kas_id' => $kasUtama,
                'kategori_id' => $katPem2,
                'tipe' => 'pemasukan',
                'jumlah' => 2000000.00,
                'tanggal' => Carbon::now()->subDays(3)->toDateString(),
                'keterangan' => 'Dana bantuan donatur untuk operasional kerja bakti.',
                'user_id' => $adminUser->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kas_id' => $kasUtama,
                'kategori_id' => $katPeng2,
                'tipe' => 'pengeluaran',
                'jumlah' => 350000.00,
                'tanggal' => Carbon::now()->subDays(2)->toDateString(),
                'keterangan' => 'Pembelian konsumsi snack & kopi kerja bakti.',
                'user_id' => $adminUser->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kas_id' => $kasSosial,
                'kategori_id' => $katPem2,
                'tipe' => 'pemasukan',
                'jumlah' => 500000.00,
                'tanggal' => Carbon::now()->subDays(8)->toDateString(),
                'keterangan' => 'Sumbangan kas peduli bencana kebakaran.',
                'user_id' => $adminUser->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);


        // ── 5. Dummy Inventaris (Kategori, Barang, Peminjaman) ───────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('inventaris_kategori')->truncate();
        DB::table('inventaris')->truncate();
        DB::table('inventaris_peminjaman')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $katBar1 = DB::table('inventaris_kategori')->insertGetId(['nama' => 'Peralatan Acara & Logistik', 'keterangan' => 'Tenda, kursi, sound system, panggung, dll.', 'created_at' => now(), 'updated_at' => now()]);
        $katBar2 = DB::table('inventaris_kategori')->insertGetId(['nama' => 'Alat Olahraga', 'keterangan' => 'Bola futsal, net voli, gawang mini, dll.', 'created_at' => now(), 'updated_at' => now()]);

        $inv1 = DB::table('inventaris')->insertGetId([
            'kode' => 'INV-LOG-001',
            'nama' => 'Tenda Gazebo Lipat 3x3m',
            'kategori_id' => $katBar1,
            'jumlah_total' => 4,
            'jumlah_tersedia' => 3,
            'kondisi' => 'baik',
            'lokasi' => 'Gudang Balai RW',
            'tanggal_pengadaan' => '2025-05-10',
            'harga_satuan' => 650000.00,
            'keterangan' => 'Tenda praktis untuk posko atau pendaftaran kegiatan.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $inv2 = DB::table('inventaris')->insertGetId([
            'kode' => 'INV-LOG-002',
            'nama' => 'Speaker Portable & Mic Wireless',
            'kategori_id' => $katBar1,
            'jumlah_total' => 2,
            'jumlah_tersedia' => 2,
            'kondisi' => 'baik',
            'lokasi' => 'Gudang Balai RW',
            'tanggal_pengadaan' => '2025-08-20',
            'harga_satuan' => 1200000.00,
            'keterangan' => 'Sound system portable baterai rechargeable merk Advance.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $inv3 = DB::table('inventaris')->insertGetId([
            'kode' => 'INV-OLR-001',
            'nama' => 'Bola Futsal Specs Original',
            'kategori_id' => $katBar2,
            'jumlah_total' => 5,
            'jumlah_tersedia' => 5,
            'kondisi' => 'baik',
            'lokasi' => 'Sekretariat Karang Taruna',
            'tanggal_pengadaan' => '2026-02-15',
            'harga_satuan' => 250000.00,
            'keterangan' => 'Digunakan khusus untuk latihan dan turnamen internal.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Simulasikan peminjaman tenda 1 unit
        DB::table('inventaris_peminjaman')->insert([
            'inventaris_id' => $inv1,
            'peminjam' => 'Bapak Slamet (RT 03)',
            'kontak' => '081255556666',
            'jumlah' => 1,
            'tanggal_pinjam' => Carbon::now()->subDays(2)->toDateString(),
            'tanggal_kembali_rencana' => Carbon::now()->addDays(1)->toDateString(),
            'status' => 'dipinjam',
            'keterangan' => 'Dipinjam untuk keperluan hajatan keluarga.',
            'user_id' => $adminUser->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // ── 6. Lomba Peralatan Checklist (Berelasi dengan Lomba & Inventaris) ─
        DB::table('lomba_peralatan')->insert([
            [
                'lomba_id' => $lomba1->id,
                'inventaris_id' => $inv3, // Bola Futsal Specs
                'nama_alat' => 'Bola Futsal Specs',
                'jumlah_dibutuhkan' => 3,
                'status' => 'siap',
                'catatan' => 'Pastikan pompa angin sudah pas sebelum bertanding.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'lomba_id' => $lomba1->id,
                'inventaris_id' => null, // Perlengkapan non-gudang
                'nama_alat' => 'Rompi Latihan (Merah & Biru)',
                'jumlah_dibutuhkan' => 20,
                'status' => 'perlu_beli',
                'catatan' => 'Sewa atau pinjam dari kelurahan jika kas tidak cukup.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);


        // ── 7. Dummy Pengaduan Warga ──────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pengaduan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('pengaduan')->insert([
            [
                'kode_tiket' => 'LAP-202607-R9A1',
                'nama_pelapor' => 'Ibu Rahmawati',
                'phone_pelapor' => '081299990001',
                'email_pelapor' => 'rahma@mail.com',
                'kategori' => 'drainase',
                'lokasi' => 'RT 04 / RW 02 (Depan Warung Bu Rahma)',
                'judul' => 'Saluran Air Mampet & Banjir Saat Hujan',
                'isi_laporan' => 'Tumpukan sampah plastik menyumbat pipa drainase utama, sehingga air meluap ke badan jalan setinggi mata kaki setiap kali hujan lebat.',
                'foto_bukti' => null,
                'status' => 'diterima',
                'tanggapan' => null,
                'foto_penanganan' => null,
                'petugas_id' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ],
            [
                'kode_tiket' => 'LAP-202607-K3B9',
                'nama_pelapor' => 'Bapak Joko Widodo',
                'phone_pelapor' => '081299990002',
                'email_pelapor' => 'joko@mail.com',
                'kategori' => 'lampu_jalan',
                'lokasi' => 'Pojok Lapangan Bulutangkis RT 02',
                'judul' => 'Lampu Jalan Utama Mati Total',
                'isi_laporan' => 'Lampu merkuri di atas tiang listrik lapangan sudah mati selama 1 minggu, daerah tersebut menjadi gelap gulita saat malam hari.',
                'foto_bukti' => null,
                'status' => 'diproses',
                'tanggapan' => 'Laporan dikonfirmasi oleh pengurus. Petugas seksi sarana dan prasarana sedang mengajukan permohonan penggantian lampu bohlam ke Kelurahan.',
                'foto_penanganan' => null,
                'petugas_id' => $adminUser->id,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2)
            ],
            [
                'kode_tiket' => 'LAP-202607-X1Z8',
                'nama_pelapor' => 'Andri Susanto',
                'phone_pelapor' => '081299990003',
                'email_pelapor' => 'andri.s@mail.com',
                'kategori' => 'sampah',
                'lokasi' => 'Tanah Kosong Belakang Pos Ronda RT 05',
                'judul' => 'Tumpukan Sampah Liar Menumpuk & Bau',
                'isi_laporan' => 'Beberapa oknum membuang sampah sembarangan di lahan kosong sehingga menyebabkan bau menyengat dan mengundang lalat ke pemukiman.',
                'foto_bukti' => null,
                'status' => 'selesai',
                'tanggapan' => 'Pengurus Karang Taruna bersama warga RT 05 telah menyelenggarakan kerja bakti bersih sampah liar dan memasang plang larangan membuang sampah di lokasi tersebut.',
                'foto_penanganan' => null,
                'petugas_id' => $adminUser->id,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(1)
            ],
        ]);

        // ── 8. Re-seed Polling Dummy (Memanggil Seeder Polling yang Dibuat) ───
        $this->call(PollingDummySeeder::class);

        $this->command->info('=== DATABASE BERHASIL DIISI DENGAN DUMMY DATA LENGKAP ===');
    }
}
