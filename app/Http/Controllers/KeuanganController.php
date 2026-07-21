<?php

namespace App\Http\Controllers;

use App\Models\KeuanganKas;
use App\Models\KeuanganKategori;
use App\Models\KeuanganTransaksi;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard Keuangan
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $totalPemasukan = KeuanganTransaksi::where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = KeuanganTransaksi::where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoTotal = KeuanganKas::sum('saldo');
        $kasList = KeuanganKas::orderBy('nama')->get();

        // Data grafik: pemasukan & pengeluaran tiap bulan di tahun ini
        $monthlyStats = KeuanganTransaksi::selectRaw('MONTH(tanggal) as bulan, tipe, SUM(jumlah) as total')
            ->whereYear('tanggal', date('Y'))
            ->groupBy('bulan', 'tipe')
            ->get();

        $chartLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $pemasukanData = array_fill(0, 12, 0);
        $pengeluaranData = array_fill(0, 12, 0);

        foreach ($monthlyStats as $stat) {
            $idx = $stat->bulan - 1;
            if ($stat->tipe === 'pemasukan') {
                $pemasukanData[$idx] = (float)$stat->total;
            } else {
                $pengeluaranData[$idx] = (float)$stat->total;
            }
        }

        $recentTransactions = KeuanganTransaksi::with(['kas', 'kategori'])
            ->latest('tanggal')
            ->latest('id')
            ->take(6)->get();

        return view('admin.keuangan.dashboard', compact(
            'totalPemasukan', 'totalPengeluaran', 'saldoTotal',
            'kasList', 'chartLabels', 'pemasukanData', 'pengeluaranData',
            'recentTransactions'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Kategori CRUD
    |--------------------------------------------------------------------------
    */
    public function indexKategori()
    {
        $kategori = KeuanganKategori::withCount('transaksi')->orderBy('nama')->paginate(12);
        return view('admin.keuangan.kategori.index', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);
        KeuanganKategori::create($validated);
        return redirect()->route('admin.keuangan.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function updateKategori(Request $request, KeuanganKategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tipe' => 'required|in:pemasukan,pengeluaran',
        ]);
        $kategori->update($validated);
        return redirect()->route('admin.keuangan.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyKategori(KeuanganKategori $kategori)
    {
        if ($kategori->transaksi()->count() > 0) {
            return redirect()->route('admin.keuangan.kategori.index')->with('error', 'Kategori ini tidak bisa dihapus karena masih memiliki riwayat transaksi.');
        }
        $kategori->delete();
        return redirect()->route('admin.keuangan.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | Akun Kas CRUD
    |--------------------------------------------------------------------------
    */
    public function indexKas()
    {
        $kas = KeuanganKas::withCount('transaksi')->orderBy('nama')->get();
        return view('admin.keuangan.kas.index', compact('kas'));
    }

    public function storeKas(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'saldo' => 'nullable|numeric|min:0',
        ]);
        $validated['saldo'] = $validated['saldo'] ?? 0;
        KeuanganKas::create($validated);
        return redirect()->route('admin.keuangan.kas.index')->with('success', 'Akun Kas berhasil ditambahkan!');
    }

    public function updateKas(Request $request, KeuanganKas$ka)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);
        $ka->update($validated);
        return redirect()->route('admin.keuangan.kas.index')->with('success', 'Akun Kas berhasil diperbarui!');
    }

    public function destroyKas(KeuanganKas $ka)
    {
        if ($ka->transaksi()->count() > 0) {
            return redirect()->route('admin.keuangan.kas.index')->with('error', 'Akun Kas tidak bisa dihapus karena memiliki riwayat transaksi.');
        }
        $ka->delete();
        return redirect()->route('admin.keuangan.kas.index')->with('success', 'Akun Kas berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | Pemasukan CRUD
    |--------------------------------------------------------------------------
    */
    public function indexPemasukan()
    {
        $pemasukan = KeuanganTransaksi::with(['kas', 'kategori'])
            ->where('tipe', 'pemasukan')
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15);
        $kasList = KeuanganKas::orderBy('nama')->get();
        $kategoriList = KeuanganKategori::where('tipe', 'pemasukan')->orderBy('nama')->get();
        return view('admin.keuangan.pemasukan.index', compact('pemasukan', 'kasList', 'kategoriList'));
    }

    public function storePemasukan(Request $request)
    {
        $validated = $request->validate([
            'kas_id' => 'required|exists:keuangan_kas,id',
            'kategori_id' => 'required|exists:keuangan_kategori,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
        ]);

        DB::transaction(function () use ($validated, $request) {
            if ($request->hasFile('bukti_foto')) {
                $validated['bukti_foto'] = ImageUploadService::uploadThumbnail($request->file('bukti_foto'), 'keuangan');
            }
            $validated['tipe'] = 'pemasukan';
            $validated['user_id'] = auth()->id();

            KeuanganTransaksi::create($validated);

            // Tambah saldo kas sesuai jumlah pemasukan
            $kas = KeuanganKas::findOrFail($validated['kas_id']);
            $kas->increment('saldo', $validated['jumlah']);
        });

        return redirect()->route('admin.keuangan.pemasukan.index')->with('success', 'Catatan pemasukan berhasil disimpan!');
    }

    public function updatePemasukan(Request $request, KeuanganTransaksi $pemasukan)
    {
        $validated = $request->validate([
            'kas_id' => 'required|exists:keuangan_kas,id',
            'kategori_id' => 'required|exists:keuangan_kategori,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
        ]);

        DB::transaction(function () use ($validated, $request, $pemasukan) {
            // Kembalikan dulu saldo lama ke kas asal
            $oldKas = KeuanganKas::findOrFail($pemasukan->kas_id);
            $oldKas->decrement('saldo', (float) $pemasukan->jumlah);

            if ($request->hasFile('bukti_foto')) {
                if ($pemasukan->bukti_foto) {
                    Storage::disk('public')->delete($pemasukan->bukti_foto);
                }
                $validated['bukti_foto'] = ImageUploadService::uploadThumbnail($request->file('bukti_foto'), 'keuangan');
            }

            $pemasukan->update($validated);

            // Terapkan saldo baru ke kas tujuan
            $newKas = KeuanganKas::findOrFail($validated['kas_id']);
            $newKas->increment('saldo', $validated['jumlah']);
        });

        return redirect()->route('admin.keuangan.pemasukan.index')->with('success', 'Catatan pemasukan berhasil diperbarui!');
    }

    public function destroyPemasukan(KeuanganTransaksi $pemasukan)
    {
        DB::transaction(function () use ($pemasukan) {
            $kas = KeuanganKas::findOrFail($pemasukan->kas_id);
            $kas->decrement('saldo', (float) $pemasukan->jumlah);

            if ($pemasukan->bukti_foto) {
                Storage::disk('public')->delete($pemasukan->bukti_foto);
            }
            $pemasukan->delete();
        });

        return redirect()->route('admin.keuangan.pemasukan.index')->with('success', 'Catatan pemasukan berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | Pengeluaran CRUD
    |--------------------------------------------------------------------------
    */
    public function indexPengeluaran()
    {
        $pengeluaran = KeuanganTransaksi::with(['kas', 'kategori'])
            ->where('tipe', 'pengeluaran')
            ->latest('tanggal')
            ->latest('id')
            ->paginate(15);
        $kasList = KeuanganKas::orderBy('nama')->get();
        $kategoriList = KeuanganKategori::where('tipe', 'pengeluaran')->orderBy('nama')->get();
        return view('admin.keuangan.pengeluaran.index', compact('pengeluaran', 'kasList', 'kategoriList'));
    }

    public function storePengeluaran(Request $request)
    {
        $validated = $request->validate([
            'kas_id' => 'required|exists:keuangan_kas,id',
            'kategori_id' => 'required|exists:keuangan_kategori,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
        ]);

        $kas = KeuanganKas::findOrFail($validated['kas_id']);
        if ($kas->saldo < $validated['jumlah']) {
            return back()->with('error', 'Gagal! Saldo pada ' . $kas->nama . ' tidak mencukupi. (Saldo: Rp' . number_format((float) $kas->saldo, 0, ',', '.') . ')')->withInput();
        }

        DB::transaction(function () use ($validated, $request, $kas) {
            if ($request->hasFile('bukti_foto')) {
                $validated['bukti_foto'] = ImageUploadService::uploadThumbnail($request->file('bukti_foto'), 'keuangan');
            }
            $validated['tipe'] = 'pengeluaran';
            $validated['user_id'] = auth()->id();

            KeuanganTransaksi::create($validated);

            // Kurangi saldo kas sesuai jumlah pengeluaran
            $kas->decrement('saldo', $validated['jumlah']);
        });

        return redirect()->route('admin.keuangan.pengeluaran.index')->with('success', 'Catatan pengeluaran berhasil disimpan!');
    }

    public function updatePengeluaran(Request $request, KeuanganTransaksi $pengeluaran)
    {
        $validated = $request->validate([
            'kas_id' => 'required|exists:keuangan_kas,id',
            'kategori_id' => 'required|exists:keuangan_kategori,id',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
        ]);

        $newKas = KeuanganKas::findOrFail($validated['kas_id']);
        // Cek apakah saldo kas masih cukup setelah ada perubahan
        $adjustedBalance = $newKas->saldo;
        if ($pengeluaran->kas_id === $newKas->id) {
            $adjustedBalance += $pengeluaran->jumlah; // add back old amount
        }
        if ($adjustedBalance < $validated['jumlah']) {
            return back()->with('error', 'Gagal! Saldo pada ' . $newKas->nama . ' tidak mencukupi untuk penyesuaian ini.')->withInput();
        }

        DB::transaction(function () use ($validated, $request, $pengeluaran, $newKas) {
            // Kembalikan dulu saldo lama ke kas asal
            $oldKas = KeuanganKas::findOrFail($pengeluaran->kas_id);
            $oldKas->increment('saldo', (float) $pengeluaran->jumlah);

            if ($request->hasFile('bukti_foto')) {
                if ($pengeluaran->bukti_foto) {
                    Storage::disk('public')->delete($pengeluaran->bukti_foto);
                }
                $validated['bukti_foto'] = ImageUploadService::uploadThumbnail($request->file('bukti_foto'), 'keuangan');
            }

            $pengeluaran->update($validated);

            // Terapkan saldo baru ke kas tujuan
            $newKas = KeuanganKas::findOrFail($validated['kas_id']);
            $newKas->decrement('saldo', $validated['jumlah']);
        });

        return redirect()->route('admin.keuangan.pengeluaran.index')->with('success', 'Catatan pengeluaran berhasil diperbarui!');
    }

    public function destroyPengeluaran(KeuanganTransaksi $pengeluaran)
    {
        DB::transaction(function () use ($pengeluaran) {
            $kas = KeuanganKas::findOrFail($pengeluaran->kas_id);
            $kas->increment('saldo', (float) $pengeluaran->jumlah);

            if ($pengeluaran->bukti_foto) {
                Storage::disk('public')->delete($pengeluaran->bukti_foto);
            }
            $pengeluaran->delete();
        });

        return redirect()->route('admin.keuangan.pengeluaran.index')->with('success', 'Catatan pengeluaran berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | Laporan Keuangan & Ekspor Excel (CSV)
    |--------------------------------------------------------------------------
    */
    public function laporan(Request $request)
    {
        // Validasi & sanitasi parameter filter — cegah manipulasi query
        $request->validate([
            'start_date'  => 'nullable|date_format:Y-m-d',
            'end_date'    => 'nullable|date_format:Y-m-d',
            'tipe'        => 'nullable|in:pemasukan,pengeluaran',
            'kas_id'      => 'nullable|integer|exists:keuangan_kas,id',
            'kategori_id' => 'nullable|integer|exists:keuangan_kategori,id',
        ]);

        $startDate  = $request->input('start_date', date('Y-m-01'));
        $endDate    = $request->input('end_date', date('Y-m-d'));
        $tipe       = $request->input('tipe');
        $kasId      = $request->input('kas_id');
        $kategoriId = $request->input('kategori_id');

        $query = KeuanganTransaksi::with(['kas', 'kategori'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($tipe) $query->where('tipe', $tipe);
        if ($kasId) $query->where('kas_id', $kasId);
        if ($kategoriId) $query->where('kategori_id', $kategoriId);

        $transaksi = $query->orderBy('tanggal')->orderBy('id')->get();

        $kasList = KeuanganKas::orderBy('nama')->get();
        $kategoriList = KeuanganKategori::orderBy('nama')->get();

        // Calculate totals for report summary
        $totalPemasukan  = $transaksi->where('tipe', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $transaksi->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldoAkhir      = $totalPemasukan - $totalPengeluaran;

        return view('admin.keuangan.laporan.index', compact(
            'transaksi', 'kasList', 'kategoriList',
            'startDate', 'endDate', 'tipe', 'kasId', 'kategoriId',
            'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'
        ));
    }

    public function exportLaporan(Request $request)
    {
        // Validasi & sanitasi parameter filter — sama seperti laporan()
        $request->validate([
            'start_date'  => 'nullable|date_format:Y-m-d',
            'end_date'    => 'nullable|date_format:Y-m-d',
            'tipe'        => 'nullable|in:pemasukan,pengeluaran',
            'kas_id'      => 'nullable|integer|exists:keuangan_kas,id',
            'kategori_id' => 'nullable|integer|exists:keuangan_kategori,id',
        ]);

        $startDate  = $request->input('start_date', date('Y-m-01'));
        $endDate    = $request->input('end_date', date('Y-m-d'));
        $tipe       = $request->input('tipe');
        $kasId      = $request->input('kas_id');
        $kategoriId = $request->input('kategori_id');

        $query = KeuanganTransaksi::with(['kas', 'kategori'])
            ->whereBetween('tanggal', [$startDate, $endDate]);

        if ($tipe) $query->where('tipe', $tipe);
        if ($kasId) $query->where('kas_id', $kasId);
        if ($kategoriId) $query->where('kategori_id', $kategoriId);

        $transaksi = $query->orderBy('tanggal')->orderBy('id')->get();

        // CSV Header configuration
        $filename = "Laporan_Keuangan_Karang_Taruna_{$startDate}_s.d_{$endDate}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'Tanggal', 'Keterangan', 'Kategori', 'Akun Kas', 'Tipe', 'Pemasukan (Rp)', 'Pengeluaran (Rp)', 'Saldo Akumulatif (Rp)'];

        $callback = function() use($transaksi, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for correct Indonesian characters display in Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Mencegah CSV/Formula Injection: kalau teks diawali =, +, -, @, tab, atau CR,
            // Excel/Sheets bisa menafsirkannya sebagai formula alih-alih teks biasa.
            // Kita tambahkan apostrof di depan supaya selalu dibaca sebagai teks.
            $sanitize = function ($value) {
                $value = (string) $value;
                if ($value !== '' && preg_match('/^[=+\-@\t\r]/', $value)) {
                    return "'" . $value;
                }
                return $value;
            };

            fputcsv($file, ['BUKU KAS UMUM - KARANG TARUNA']);
            fputcsv($file, ['Periode:', $columns[1] ?? '']);
            fputcsv($file, []);
            fputcsv($file, $columns);

            $saldo = 0;
            $no = 1;
            foreach ($transaksi as $t) {
                $pemasukan = 0;
                $pengeluaran = 0;
                if ($t->tipe === 'pemasukan') {
                    $pemasukan = $t->jumlah;
                    $saldo += $t->jumlah;
                } else {
                    $pengeluaran = $t->jumlah;
                    $saldo -= $t->jumlah;
                }

                fputcsv($file, [
                    $no++,
                    $t->tanggal->format('d/m/Y'),
                    $sanitize($t->keterangan ?? '-'),
                    $sanitize($t->kategori->nama ?? '-'),
                    $sanitize($t->kas->nama ?? '-'),
                    ucfirst($t->tipe),
                    $pemasukan,
                    $pengeluaran,
                    $saldo
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
