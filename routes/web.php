<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\PollingController;
use App\Http\Controllers\AnggotaPollingController;

/*
|--------------------------------------------------------------------------
| Public Routes (Warga - tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/pengumuman', [PublicController::class, 'pengumumanIndex'])->name('public.pengumuman');
Route::get('/pengumuman/{pengumuman}', [PublicController::class, 'pengumumanShow'])->name('public.pengumuman.show');
Route::get('/kegiatan', [PublicController::class, 'kegiatanIndex'])->name('public.kegiatan');
Route::get('/kegiatan/{kegiatan}', [PublicController::class, 'kegiatanShow'])->name('public.kegiatan.show');
Route::get('/anggota', [PublicController::class, 'anggota'])->name('public.anggota');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('public.galeri');
Route::get('/lomba', [PublicController::class, 'lombaIndex'])->name('public.lomba');
Route::get('/kalender', [PublicController::class, 'kalenderIndex'])->name('public.kalender');
Route::get('/api/kalender/events', [PublicController::class, 'kalenderEvents'])->name('public.kalender.events');
Route::get('/pengaduan', [\App\Http\Controllers\PublicPengaduanController::class, 'index'])->middleware('throttle:20,1')->name('public.pengaduan');
Route::post('/pengaduan', [\App\Http\Controllers\PublicPengaduanController::class, 'store'])->middleware('throttle:5,1')->name('public.pengaduan.store');
Route::get('/polling', [PublicController::class, 'pollingIndex'])->name('public.polling');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {

    // Dashboard (semua user yang login bisa akses)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengumuman
    Route::resource('pengumuman', PengumumanController::class)
        ->middleware('menu.access:pengumuman');

    // Kegiatan
    Route::resource('kegiatan', KegiatanController::class)
        ->middleware('menu.access:kegiatan');

    // Dokumentasi
    Route::get('/dokumentasi', [DokumentasiController::class, 'index'])
        ->name('dokumentasi.index')->middleware('menu.access:dokumentasi');
    Route::post('/dokumentasi', [DokumentasiController::class, 'store'])
        ->name('dokumentasi.store')->middleware('menu.access:dokumentasi');
    Route::delete('/dokumentasi/{dokumentasi}', [DokumentasiController::class, 'destroy'])
        ->name('dokumentasi.destroy')->middleware('menu.access:dokumentasi');

    // Anggota
    Route::resource('anggota', AnggotaController::class)
        ->parameters(['anggota' => 'anggota'])
        ->middleware('menu.access:anggota');

    // Users (admin only via menu permission)
    Route::resource('users', UserController::class)
        ->middleware('menu.access:users');

    // Roles & Permissions (admin only via menu permission)
    Route::resource('roles', RoleController::class)
        ->middleware('menu.access:roles');

    // Keuangan Module
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\KeuanganController::class, 'dashboard'])
            ->name('dashboard')->middleware('menu.access:keuangan_dashboard');

        // Kategori Keuangan
        Route::get('/kategori', [\App\Http\Controllers\KeuanganController::class, 'indexKategori'])
            ->name('kategori.index')->middleware('menu.access:keuangan_kategori');
        Route::post('/kategori', [\App\Http\Controllers\KeuanganController::class, 'storeKategori'])
            ->name('kategori.store')->middleware('menu.access:keuangan_kategori');
        Route::put('/kategori/{kategori}', [\App\Http\Controllers\KeuanganController::class, 'updateKategori'])
            ->name('kategori.update')->middleware('menu.access:keuangan_kategori');
        Route::delete('/kategori/{kategori}', [\App\Http\Controllers\KeuanganController::class, 'destroyKategori'])
            ->name('kategori.destroy')->middleware('menu.access:keuangan_kategori');

        // Akun Kas
        Route::get('/kas', [\App\Http\Controllers\KeuanganController::class, 'indexKas'])
            ->name('kas.index')->middleware('menu.access:keuangan_kas');
        Route::post('/kas', [\App\Http\Controllers\KeuanganController::class, 'storeKas'])
            ->name('kas.store')->middleware('menu.access:keuangan_kas');
        Route::put('/kas/{ka}', [\App\Http\Controllers\KeuanganController::class, 'updateKas'])
            ->name('kas.update')->middleware('menu.access:keuangan_kas');
        Route::delete('/kas/{ka}', [\App\Http\Controllers\KeuanganController::class, 'destroyKas'])
            ->name('kas.destroy')->middleware('menu.access:keuangan_kas');

        // Pemasukan
        Route::get('/pemasukan', [\App\Http\Controllers\KeuanganController::class, 'indexPemasukan'])
            ->name('pemasukan.index')->middleware('menu.access:keuangan_pemasukan');
        Route::post('/pemasukan', [\App\Http\Controllers\KeuanganController::class, 'storePemasukan'])
            ->name('pemasukan.store')->middleware('menu.access:keuangan_pemasukan');
        Route::put('/pemasukan/{pemasukan}', [\App\Http\Controllers\KeuanganController::class, 'updatePemasukan'])
            ->name('pemasukan.update')->middleware('menu.access:keuangan_pemasukan');
        Route::delete('/pemasukan/{pemasukan}', [\App\Http\Controllers\KeuanganController::class, 'destroyPemasukan'])
            ->name('pemasukan.destroy')->middleware('menu.access:keuangan_pemasukan');

        // Pengeluaran
        Route::get('/pengeluaran', [\App\Http\Controllers\KeuanganController::class, 'indexPengeluaran'])
            ->name('pengeluaran.index')->middleware('menu.access:keuangan_pengeluaran');
        Route::post('/pengeluaran', [\App\Http\Controllers\KeuanganController::class, 'storePengeluaran'])
            ->name('pengeluaran.store')->middleware('menu.access:keuangan_pengeluaran');
        Route::put('/pengeluaran/{pengeluaran}', [\App\Http\Controllers\KeuanganController::class, 'updatePengeluaran'])
            ->name('pengeluaran.update')->middleware('menu.access:keuangan_pengeluaran');
        Route::delete('/pengeluaran/{pengeluaran}', [\App\Http\Controllers\KeuanganController::class, 'destroyPengeluaran'])
            ->name('pengeluaran.destroy')->middleware('menu.access:keuangan_pengeluaran');

        // Laporan Keuangan
        Route::get('/laporan', [\App\Http\Controllers\KeuanganController::class, 'laporan'])
            ->name('laporan.index')->middleware('menu.access:keuangan_laporan');
        Route::get('/laporan/export', [\App\Http\Controllers\KeuanganController::class, 'exportLaporan'])
            ->name('laporan.export')->middleware('menu.access:keuangan_laporan');

        // Iuran Warga / Kas Otomatis
        Route::prefix('iuran')->name('iuran.')->middleware('menu.access:keuangan_iuran')->group(function () {
            Route::get('/', [\App\Http\Controllers\KeuanganIuranController::class, 'index'])->name('index');
            Route::post('/generate', [\App\Http\Controllers\KeuanganIuranController::class, 'generate'])->name('generate');
            Route::post('/{iuran}/bayar', [\App\Http\Controllers\KeuanganIuranController::class, 'bayar'])->name('bayar');
            Route::post('/{iuran}/batal', [\App\Http\Controllers\KeuanganIuranController::class, 'batalBayar'])->name('batal');
            Route::delete('/{iuran}', [\App\Http\Controllers\KeuanganIuranController::class, 'destroy'])->name('destroy');
        });
    });

    // Inventaris 
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        // Daftar Barang
        Route::middleware('menu.access:inventaris-daftar')->group(function () {
            Route::get('/', [InventarisController::class, 'index'])->name('index');
            Route::get('/create', [InventarisController::class, 'create'])->name('create');
            Route::post('/', [InventarisController::class, 'store'])->name('store');
            Route::get('/{inventaris}/edit', [InventarisController::class, 'edit'])->name('edit');
            Route::put('/{inventaris}', [InventarisController::class, 'update'])->name('update');
            Route::delete('/{inventaris}', [InventarisController::class, 'destroy'])->name('destroy');
        });

        // Kategori
        Route::middleware('menu.access:inventaris-kategori')->group(function () {
            Route::get('/kategori', [InventarisController::class, 'indexKategori'])->name('kategori.index');
            Route::post('/kategori', [InventarisController::class, 'storeKategori'])->name('kategori.store');
            Route::put('/kategori/{kategori}', [InventarisController::class, 'updateKategori'])->name('kategori.update');
            Route::delete('/kategori/{kategori}', [InventarisController::class, 'destroyKategori'])->name('kategori.destroy');
        });

        // Peminjaman
        Route::middleware('menu.access:inventaris-peminjaman')->group(function () {
            Route::get('/peminjaman', [InventarisController::class, 'indexPeminjaman'])->name('peminjaman.index');
            Route::post('/peminjaman', [InventarisController::class, 'storePeminjaman'])->name('peminjaman.store');
            Route::patch('/peminjaman/{peminjaman}/kembalikan', [InventarisController::class, 'kembalikanPeminjaman'])->name('peminjaman.kembalikan');
            Route::delete('/peminjaman/{peminjaman}', [InventarisController::class, 'destroyPeminjaman'])->name('peminjaman.destroy');
        });
    });

    // Lomba 
    Route::resource('lomba', LombaController::class)->middleware('menu.access:lomba');
    Route::post('/lomba/{lomba}/peralatan', [LombaController::class, 'storePeralatan'])->name('lomba.peralatan.store')->middleware('menu.access:lomba');
    Route::put('/lomba/peralatan/{peralatan}', [LombaController::class, 'updatePeralatan'])->name('lomba.peralatan.update')->middleware('menu.access:lomba');
    Route::delete('/lomba/peralatan/{peralatan}', [LombaController::class, 'destroyPeralatan'])->name('lomba.peralatan.destroy')->middleware('menu.access:lomba');
    Route::post('/lomba/{lomba}/peserta', [LombaController::class, 'storePeserta'])->name('lomba.peserta.store')->middleware('menu.access:lomba');
    Route::put('/lomba/peserta/{peserta}', [LombaController::class, 'updatePeserta'])->name('lomba.peserta.update')->middleware('menu.access:lomba');
    Route::delete('/lomba/peserta/{peserta}', [LombaController::class, 'destroyPeserta'])->name('lomba.peserta.destroy')->middleware('menu.access:lomba');

    // Pengaduan Warga 
    Route::resource('pengaduan', \App\Http\Controllers\PengaduanController::class)->middleware('menu.access:pengaduan');

    // Polling 
    Route::resource('polling', PollingController::class)->middleware('menu.access:polling');
    Route::get('polling/{polling}/hasil', [PollingController::class, 'hasil'])
        ->name('polling.hasil')->middleware('menu.access:polling');

    // Area Anggota (semua user yang login) 
    Route::prefix('anggota-area')->name('anggota.')->group(function () {
        Route::get('/polling', [AnggotaPollingController::class, 'index'])->name('polling');
        Route::get('/polling/{polling}', [AnggotaPollingController::class, 'show'])->name('polling.show');
        Route::post('/polling/{polling}/vote', [AnggotaPollingController::class, 'vote'])->name('polling.vote');
    });
});

