<?php

namespace App\Http\Controllers;

use App\Models\Lomba;
use App\Models\LombaPeralatan;
use App\Models\LombaPeserta;
use App\Models\Kegiatan;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class LombaController extends Controller
{
    public function index(Request $request)
    {
        $query = Lomba::with('kegiatan')->latest('tanggal');

        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $lomba = $query->paginate(12)->withQueryString();
        $kegiatanList = Kegiatan::orderByDesc('tanggal')->get();

        return view('admin.lomba.index', compact('lomba', 'kegiatanList'));
    }

    public function create()
    {
        $kegiatanList = Kegiatan::orderByDesc('tanggal')->get();
        return view('admin.lomba.create', compact('kegiatanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id'       => 'required|exists:kegiatan,id',
            'nama'              => 'required|string|max:255',
            'kategori'          => 'nullable|string|max:255',
            'deskripsi'         => 'nullable|string',
            'tanggal'           => 'required|date',
            'waktu_mulai'       => 'nullable|date_format:H:i',
            'lokasi'            => 'nullable|string|max:255',
            'penanggung_jawab'  => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();
        $lomba = Lomba::create($validated);

        return redirect()->route('admin.lomba.show', $lomba)
            ->with('success', 'Lomba berhasil ditambahkan! Sekarang tambahkan checklist peralatan & peserta.');
    }

    public function show(Lomba $lomba)
    {
        $lomba->load(['kegiatan', 'peralatan.inventaris', 'peserta' => function ($q) {
            $q->orderByRaw('juara IS NULL, juara ASC')->orderBy('nomor_urut');
        }]);
        $inventarisList = Inventaris::orderBy('nama')->get();

        return view('admin.lomba.show', compact('lomba', 'inventarisList'));
    }

    public function edit(Lomba $lomba)
    {
        $kegiatanList = Kegiatan::orderByDesc('tanggal')->get();
        return view('admin.lomba.edit', compact('lomba', 'kegiatanList'));
    }

    public function update(Request $request, Lomba $lomba)
    {
        $validated = $request->validate([
            'kegiatan_id'       => 'required|exists:kegiatan,id',
            'nama'              => 'required|string|max:255',
            'kategori'          => 'nullable|string|max:255',
            'deskripsi'         => 'nullable|string',
            'tanggal'           => 'required|date',
            'waktu_mulai'       => 'nullable|date_format:H:i',
            'lokasi'            => 'nullable|string|max:255',
            'penanggung_jawab'  => 'nullable|string|max:255',
        ]);

        $lomba->update($validated);

        return redirect()->route('admin.lomba.show', $lomba)
            ->with('success', 'Data lomba berhasil diperbarui!');
    }

    public function destroy(Lomba $lomba)
    {
        // peralatan & peserta ikut terhapus otomatis (cascadeOnDelete di migration)
        $lomba->delete();

        return redirect()->route('admin.lomba.index')
            ->with('success', 'Lomba berhasil dihapus!');
    }

    // ═══════════════════════════════════════════
    //  PERALATAN
    // ═══════════════════════════════════════════

    public function storePeralatan(Request $request, Lomba $lomba)
    {
        $validated = $request->validate([
            'inventaris_id'     => 'nullable|exists:inventaris,id',
            'nama_alat'         => 'required|string|max:255',
            'jumlah_dibutuhkan' => 'required|integer|min:1',
            'status'            => 'required|in:perlu_beli,perlu_pinjam,tersedia,siap',
            'catatan'           => 'nullable|string',
        ]);
        $validated['lomba_id'] = $lomba->id;

        LombaPeralatan::create($validated);

        return back()->with('success', 'Peralatan ditambahkan ke checklist!');
    }

    public function updatePeralatan(Request $request, LombaPeralatan $peralatan)
    {
        // Pastikan peralatan ini benar-benar milik lomba yang sedang diedit
        // (cegah orang iseng ganti ID di URL untuk edit data lomba lain)
        $lomba = Lomba::findOrFail($request->input('lomba_id', $peralatan->lomba_id));
        abort_unless($peralatan->lomba_id === $lomba->id, 403, 'Akses ditolak.');

        $validated = $request->validate([
            'inventaris_id'     => 'nullable|exists:inventaris,id',
            'nama_alat'         => 'required|string|max:255',
            'jumlah_dibutuhkan' => 'required|integer|min:1',
            'status'            => 'required|in:perlu_beli,perlu_pinjam,tersedia,siap',
            'catatan'           => 'nullable|string',
        ]);

        $peralatan->update($validated);

        return back()->with('success', 'Checklist peralatan diperbarui!');
    }

    public function destroyPeralatan(LombaPeralatan $peralatan)
    {
        // Verifikasi kepemilikan sebelum hapus
        abort_unless(Lomba::where('id', $peralatan->lomba_id)->exists(), 403, 'Akses ditolak.');
        $peralatan->delete();
        return back()->with('success', 'Item peralatan dihapus dari checklist!');
    }

    // ═══════════════════════════════════════════
    //  PESERTA & JUARA
    // ═══════════════════════════════════════════

    public function storePeserta(Request $request, Lomba $lomba)
    {
        $validated = $request->validate([
            'nama_peserta'   => 'required|string|max:255',
            'nomor_urut'     => 'nullable|string|max:50',
            'kategori_usia'  => 'nullable|string|max:100',
            'kontak'         => 'nullable|string|max:50',
        ]);
        $validated['lomba_id'] = $lomba->id;

        LombaPeserta::create($validated);

        return back()->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function updatePeserta(Request $request, LombaPeserta $peserta)
    {
        // Pastikan peserta ini milik lomba yang valid
        abort_unless(Lomba::where('id', $peserta->lomba_id)->exists(), 403, 'Akses ditolak.');

        $validated = $request->validate([
            'nama_peserta'   => 'required|string|max:255',
            'nomor_urut'     => 'nullable|string|max:50',
            'kategori_usia'  => 'nullable|string|max:100',
            'kontak'         => 'nullable|string|max:50',
            'juara'          => 'nullable|string|max:50',
        ]);

        $peserta->update($validated);

        return back()->with('success', 'Data peserta diperbarui!');
    }

    public function destroyPeserta(LombaPeserta $peserta)
    {
        // Pastikan peserta ini milik lomba yang valid sebelum dihapus
        abort_unless(Lomba::where('id', $peserta->lomba_id)->exists(), 403, 'Akses ditolak.');
        $peserta->delete();
        return back()->with('success', 'Peserta dihapus dari daftar!');
    }
}
