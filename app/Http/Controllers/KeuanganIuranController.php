<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\KeuanganIuran;
use App\Models\KeuanganKas;
use App\Models\KeuanganKategori;
use App\Models\KeuanganTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganIuranController extends Controller
{
    /**
     * Tampilkan halaman utama pengelolaan Iuran
     */
    public function index(Request $request)
    {
        $request->validate([
            'bulan'  => 'nullable|integer|between:1,12',
            'tahun'  => 'nullable|integer|min:2020|max:2099',
            'status' => 'nullable|in:semua,belum_bayar,lunas,dibatalkan',
            'search' => 'nullable|string|max:100',
        ]);

        $selectedBulan = (int) ($request->input('bulan', date('n')));
        $selectedTahun = (int) ($request->input('tahun', date('Y')));
        $selectedStatus = $request->input('status', 'semua');
        $search = $request->input('search');

        // Query untuk tabel list tagihan
        $query = KeuanganIuran::with(['anggota', 'kas', 'kategori', 'transaksi'])
            ->where('bulan', $selectedBulan)
            ->where('tahun', $selectedTahun);

        if ($selectedStatus && $selectedStatus !== 'semua') {
            $query->where('status', $selectedStatus);
        }

        if ($search) {
            $query->whereHas('anggota', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        $iuranList = $query->orderBy('status', 'asc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Ringkasan Statistik Periode Terpilih
        $baseStatQuery = KeuanganIuran::where('bulan', $selectedBulan)->where('tahun', $selectedTahun);
        $totalTagihanCount = (clone $baseStatQuery)->count();
        $totalNominalTagihan = (clone $baseStatQuery)->sum('nominal');
        
        $lunasQuery = (clone $baseStatQuery)->where('status', 'lunas');
        $totalLunasCount = (clone $lunasQuery)->count();
        $totalTerbayar = (clone $lunasQuery)->sum('nominal');

        $totalBelumBayarCount = (clone $baseStatQuery)->where('status', 'belum_bayar')->count();
        $persentaseLunas = $totalTagihanCount > 0 ? round(($totalLunasCount / $totalTagihanCount) * 100) : 0;

        // Data Matriks Rekap Tahunan (seluruh anggota & status 12 bulan di tahun terpilih)
        $anggotaMatrix = Anggota::orderBy('nama', 'asc')->get();
        $iuranTahunIni = KeuanganIuran::where('tahun', $selectedTahun)->get();

        // Mapping iuran tahun ini ke array [anggota_id][bulan] = status
        $matrixStatus = [];
        foreach ($iuranTahunIni as $iuran) {
            $matrixStatus[$iuran->anggota_id][$iuran->bulan] = $iuran->status;
        }

        $kasList = KeuanganKas::orderBy('nama')->get();
        $kategoriPemasukanList = KeuanganKategori::where('tipe', 'pemasukan')->orderBy('nama')->get();
        $daftarBulan = KeuanganIuran::$daftarBulan;

        return view('admin.keuangan.iuran.index', compact(
            'iuranList',
            'selectedBulan',
            'selectedTahun',
            'selectedStatus',
            'search',
            'totalTagihanCount',
            'totalNominalTagihan',
            'totalLunasCount',
            'totalTerbayar',
            'totalBelumBayarCount',
            'persentaseLunas',
            'anggotaMatrix',
            'matrixStatus',
            'kasList',
            'kategoriPemasukanList',
            'daftarBulan'
        ));
    }

    /**
     * Generate tagihan bulanan otomatis secara massal untuk anggota aktif
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'bulan'       => 'required|integer|between:1,12',
            'tahun'       => 'required|integer|min:2020|max:2099',
            'nominal'     => 'required|numeric|min:1000',
            'kas_id'      => 'nullable|exists:keuangan_kas,id',
            'kategori_id' => 'nullable|exists:keuangan_kategori,id',
        ]);

        $anggotaAktif = Anggota::where('aktif', true)->get();

        if ($anggotaAktif->isEmpty()) {
            return back()->with('error', 'Tidak ada anggota aktif yang dapat dibuatkan tagihan.');
        }

        $createdCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($anggotaAktif, $validated, &$createdCount, &$skippedCount) {
            foreach ($anggotaAktif as $anggota) {
                // Cek apakah tagihan sudah ada
                $exists = KeuanganIuran::where('anggota_id', $anggota->id)
                    ->where('bulan', $validated['bulan'])
                    ->where('tahun', $validated['tahun'])
                    ->exists();

                if (!$exists) {
                    KeuanganIuran::create([
                        'anggota_id'  => $anggota->id,
                        'bulan'       => $validated['bulan'],
                        'tahun'       => $validated['tahun'],
                        'nominal'     => $validated['nominal'],
                        'status'      => 'belum_bayar',
                        'kas_id'      => $validated['kas_id'] ?? null,
                        'kategori_id' => $validated['kategori_id'] ?? null,
                    ]);
                    $createdCount++;
                } else {
                    $skippedCount++;
                }
            }
        });

        $namaBulan = KeuanganIuran::$daftarBulan[$validated['bulan']] ?? $validated['bulan'];

        if ($createdCount === 0) {
            return back()->with('error', "Tagihan iuran untuk bulan {$namaBulan} {$validated['tahun']} sudah pernah dibuat sebelumnya ({$skippedCount} anggota).");
        }

        $msg = "Berhasil membuat {$createdCount} tagihan iuran bulan {$namaBulan} {$validated['tahun']}.";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} anggota dilewati karena sudah memiliki tagihan).";
        }

        return redirect()->route('admin.keuangan.iuran.index', [
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
        ])->with('success', $msg);
    }

    /**
     * Tandai pembayaran iuran sebagai Lunas & hubungkan ke KeuanganTransaksi (Buku Kas)
     */
    public function bayar(Request $request, KeuanganIuran $iuran)
    {
        $validated = $request->validate([
            'kas_id'        => 'required|exists:keuangan_kas,id',
            'kategori_id'   => 'required|exists:keuangan_kategori,id',
            'tanggal_bayar' => 'required|date',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        if ($iuran->status === 'lunas') {
            return back()->with('error', 'Tagihan ini sudah berstatus lunas.');
        }

        DB::transaction(function () use ($validated, $iuran) {
            $namaAnggota = $iuran->anggota->nama ?? 'Anggota';
            $bulanNama   = $iuran->nama_bulan;
            $tahun       = $iuran->tahun;

            // 1. Buat catatan Pemasukan di KeuanganTransaksi
            $transaksi = KeuanganTransaksi::create([
                'kas_id'      => $validated['kas_id'],
                'kategori_id' => $validated['kategori_id'],
                'tipe'        => 'pemasukan',
                'jumlah'      => $iuran->nominal,
                'tanggal'     => $validated['tanggal_bayar'],
                'keterangan'  => "Iuran Kas - {$namaAnggota} ({$bulanNama} {$tahun})" . ($validated['keterangan'] ? " | {$validated['keterangan']}" : ''),
                'user_id'     => auth()->id(),
            ]);

            // 2. Tambahkan saldo kas
            $kas = KeuanganKas::findOrFail($validated['kas_id']);
            $kas->increment('saldo', (float) $iuran->nominal);

            // 3. Update status tagihan iuran
            $iuran->update([
                'status'        => 'lunas',
                'tanggal_bayar' => $validated['tanggal_bayar'],
                'kas_id'        => $validated['kas_id'],
                'kategori_id'   => $validated['kategori_id'],
                'transaksi_id'  => $transaksi->id,
                'keterangan'    => $validated['keterangan'],
            ]);
        });

        return back()->with('success', "Pembayaran iuran a.n {$iuran->anggota->nama} berhasil dicatat & masuk ke Kas!");
    }

    /**
     * Batalkan status lunas iuran & kurangi saldo kas secara otomatis
     */
    public function batalBayar(KeuanganIuran $iuran)
    {
        if ($iuran->status !== 'lunas') {
            return back()->with('error', 'Tagihan ini belum berstatus lunas.');
        }

        DB::transaction(function () use ($iuran) {
            // Jika ada transaksi kas terhubung, hapus dan kurangi saldo
            if ($iuran->transaksi_id) {
                $transaksi = KeuanganTransaksi::find($iuran->transaksi_id);
                if ($transaksi) {
                    $kas = KeuanganKas::find($transaksi->kas_id);
                    if ($kas) {
                        $kas->decrement('saldo', $transaksi->jumlah);
                    }
                    $transaksi->delete();
                }
            }

            $iuran->update([
                'status'        => 'belum_bayar',
                'tanggal_bayar' => null,
                'transaksi_id'  => null,
            ]);
        });

        return back()->with('success', "Status pembayaran iuran a.n {$iuran->anggota->nama} telah dibatalkan.");
    }

    /**
     * Hapus tagihan iuran
     */
    public function destroy(KeuanganIuran $iuran)
    {
        DB::transaction(function () use ($iuran) {
            if ($iuran->status === 'lunas' && $iuran->transaksi_id) {
                $transaksi = KeuanganTransaksi::find($iuran->transaksi_id);
                if ($transaksi) {
                    $kas = KeuanganKas::find($transaksi->kas_id);
                    if ($kas) {
                        $kas->decrement('saldo', $transaksi->jumlah);
                    }
                    $transaksi->delete();
                }
            }
            $iuran->delete();
        });

        return back()->with('success', 'Tagihan iuran berhasil dihapus.');
    }
}
