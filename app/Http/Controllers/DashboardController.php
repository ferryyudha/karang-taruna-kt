<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kegiatan;
use App\Models\Pengumuman;
use App\Models\Dokumentasi;
use App\Models\User;
use App\Models\Lomba;
use App\Models\Pengaduan;
use App\Models\KeuanganKas;
use App\Models\KeuanganTransaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedYear = (int) $request->input('tahun', date('Y'));
        $availableYears = range((int)date('Y'), 2024);

        // Basic Counts
        $stats = [
            'anggota'     => Anggota::where('aktif', true)->count(),
            'kegiatan'    => Kegiatan::whereYear('tanggal', $selectedYear)->count(),
            'pengumuman'  => Pengumuman::where('status', 'publish')->count(),
            'dokumentasi' => Dokumentasi::count(),
            'users'       => User::count(),
            'lomba'       => Lomba::whereYear('tanggal', $selectedYear)->count(),
            'pengaduan'   => Pengaduan::whereYear('created_at', $selectedYear)->count(),
        ];

        // Keuangan Summary
        $totalSaldoKas = (float) KeuanganKas::sum('saldo');
        
        $totalPemasukanTahun = (float) KeuanganTransaksi::where('tipe', 'pemasukan')
            ->whereYear('tanggal', $selectedYear)
            ->sum('jumlah');

        $totalPengeluaranTahun = (float) KeuanganTransaksi::where('tipe', 'pengeluaran')
            ->whereYear('tanggal', $selectedYear)
            ->sum('jumlah');

        $netFlowTahun = $totalPemasukanTahun - $totalPengeluaranTahun;

        // Keuangan Bulanan (Jan - Des)
        $pemasukanBulanan = KeuanganTransaksi::where('tipe', 'pemasukan')
            ->whereYear('tanggal', $selectedYear)
            ->selectRaw('MONTH(tanggal) as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $pengeluaranBulanan = KeuanganTransaksi::where('tipe', 'pengeluaran')
            ->whereYear('tanggal', $selectedYear)
            ->selectRaw('MONTH(tanggal) as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $chartLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        
        $chartPemasukan   = array_map(fn($i) => (float)($pemasukanBulanan[$i] ?? 0), range(1, 12));
        $chartPengeluaran = array_map(fn($i) => (float)($pengeluaranBulanan[$i] ?? 0), range(1, 12));

        // Kegiatan per bulan
        $kegiatanPerBulan = Kegiatan::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->whereYear('tanggal', $selectedYear)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();
        $chartKegiatan = array_map(fn($i) => (int)($kegiatanPerBulan[$i] ?? 0), range(1, 12));

        // Laporan Pengaduan Status Breakdown
        $pengaduanStatus = [
            'diterima' => Pengaduan::whereYear('created_at', $selectedYear)->where('status', 'diterima')->count(),
            'diproses' => Pengaduan::whereYear('created_at', $selectedYear)->where('status', 'diproses')->count(),
            'selesai'  => Pengaduan::whereYear('created_at', $selectedYear)->where('status', 'selesai')->count(),
            'ditolak'  => Pengaduan::whereYear('created_at', $selectedYear)->where('status', 'ditolak')->count(),
        ];

        $pengaduanTotal = array_sum($pengaduanStatus);
        $pengaduanSelesaiRate = $pengaduanTotal > 0 ? round(($pengaduanStatus['selesai'] / $pengaduanTotal) * 100, 1) : 0;

        // Tables Data
        $recentPengumuman = Pengumuman::with('user')->latest()->take(5)->get();
        $recentKegiatan   = Kegiatan::with('user')->latest()->take(5)->get();
        $upcomingKegiatan = Kegiatan::where('tanggal', '>=', now()->toDateString())->orderBy('tanggal')->take(3)->get();

        return view('admin.dashboard', compact(
            'selectedYear', 'availableYears',
            'stats', 'totalSaldoKas', 'totalPemasukanTahun', 'totalPengeluaranTahun', 'netFlowTahun',
            'chartLabels', 'chartPemasukan', 'chartPengeluaran', 'chartKegiatan',
            'pengaduanStatus', 'pengaduanTotal', 'pengaduanSelesaiRate',
            'recentPengumuman', 'recentKegiatan', 'upcomingKegiatan'
        ));
    }
}
