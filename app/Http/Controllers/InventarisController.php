<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\InventarisKategori;
use App\Models\InventarisPeminjaman;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller
{
    // ═══════════════════════════════════════════════
    //  DAFTAR INVENTARIS
    // ═══════════════════════════════════════════════

    public function index(Request $request)
    {
        $query = Inventaris::with('kategori')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kode', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $inventaris  = $query->paginate(15)->withQueryString();
        $kategoriList = InventarisKategori::orderBy('nama')->get();

        return view('admin.inventaris.index', compact('inventaris', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = InventarisKategori::orderBy('nama')->get();
        return view('admin.inventaris.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'              => 'nullable|string|max:50|unique:inventaris,kode',
            'nama'              => 'required|string|max:255',
            'kategori_id'       => 'nullable|exists:inventaris_kategori,id',
            'jumlah_total'      => 'required|integer|min:0',
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi'            => 'nullable|string|max:255',
            'tanggal_pengadaan' => 'nullable|date',
            'harga_satuan'      => 'nullable|numeric|min:0',
            'keterangan'        => 'nullable|string',
            'foto'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
        ]);

        $validated['jumlah_tersedia'] = $validated['jumlah_total'];

        if ($request->hasFile('foto')) {
            $validated['foto'] = ImageUploadService::uploadThumbnail($request->file('foto'), 'inventaris');
        }

        // Auto-generate kode jika kosong
        if (empty($validated['kode'])) {
            $validated['kode'] = 'INV-' . strtoupper(Str::random(6));
        }

        Inventaris::create($validated);

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Inventaris $inventaris)
    {
        $kategoriList = InventarisKategori::orderBy('nama')->get();
        return view('admin.inventaris.edit', compact('inventaris', 'kategoriList'));
    }

    public function update(Request $request, Inventaris $inventaris)
    {
        $validated = $request->validate([
            'kode'              => 'nullable|string|max:50|unique:inventaris,kode,' . $inventaris->id,
            'nama'              => 'required|string|max:255',
            'kategori_id'       => 'nullable|exists:inventaris_kategori,id',
            'jumlah_total'      => 'required|integer|min:0',
            'jumlah_tersedia'   => 'required|integer|min:0|lte:jumlah_total',
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi'            => 'nullable|string|max:255',
            'tanggal_pengadaan' => 'nullable|date',
            'harga_satuan'      => 'nullable|numeric|min:0',
            'keterangan'        => 'nullable|string',
            'foto'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
        ], [
            'jumlah_tersedia.lte' => 'Jumlah tersedia tidak boleh lebih besar dari jumlah total.',
        ]);

        if ($request->hasFile('foto')) {
            if ($inventaris->foto) Storage::disk('public')->delete($inventaris->foto);
            $validated['foto'] = ImageUploadService::uploadThumbnail($request->file('foto'), 'inventaris');
        }

        $inventaris->update($validated);

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Inventaris $inventaris)
    {
        if ($inventaris->foto) Storage::disk('public')->delete($inventaris->foto);
        $inventaris->delete();

        return redirect()->route('admin.inventaris.index')
            ->with('success', 'Barang berhasil dihapus!');
    }

    // ═══════════════════════════════════════════════
    //  KATEGORI
    // ═══════════════════════════════════════════════

    public function indexKategori()
    {
        $kategori = InventarisKategori::withCount('inventaris')->orderBy('nama')->paginate(20);
        return view('admin.inventaris.kategori', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255', 'keterangan' => 'nullable|string']);
        InventarisKategori::create($request->only('nama', 'keterangan'));
        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function updateKategori(Request $request, InventarisKategori $kategori)
    {
        $request->validate(['nama' => 'required|string|max:255', 'keterangan' => 'nullable|string']);
        $kategori->update($request->only('nama', 'keterangan'));
        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyKategori(InventarisKategori $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    // ═══════════════════════════════════════════════
    //  PEMINJAMAN
    // ═══════════════════════════════════════════════

    public function indexPeminjaman(Request $request)
    {
        // Auto update status terlambat
        InventarisPeminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali_rencana', '<', now()->toDateString())
            ->update(['status' => 'terlambat']);

        $query = InventarisPeminjaman::with('inventaris')->latest();
        // Whitelist status yang boleh difilter, cegah nilai sembarangan dari URL
        $allowedStatus = ['dipinjam', 'dikembalikan', 'terlambat'];
        if ($request->filled('status') && in_array($request->status, $allowedStatus)) {
            $query->where('status', $request->status);
        }

        $peminjaman    = $query->paginate(15)->withQueryString();
        $inventarisList = Inventaris::where('jumlah_tersedia', '>', 0)->orderBy('nama')->get();

        return view('admin.inventaris.peminjaman', compact('peminjaman', 'inventarisList'));
    }

    public function storePeminjaman(Request $request)
    {
        $validated = $request->validate([
            'inventaris_id'           => 'required|exists:inventaris,id',
            'peminjam'                => 'required|string|max:255',
            'kontak'                  => 'nullable|string|max:50',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keterangan'              => 'nullable|string',
        ]);

        // Dibungkus transaction + lockForUpdate supaya kalau ada 2 orang input
        // peminjaman barang yang sama persis di waktu berdekatan, stoknya tetap
        // konsisten (nggak bisa jadi minus).
        $error = null;
        DB::transaction(function () use ($validated, &$error) {
            $barang = Inventaris::where('id', $validated['inventaris_id'])->lockForUpdate()->firstOrFail();

            if ($barang->jumlah_tersedia < $validated['jumlah']) {
                $error = 'Jumlah melebihi stok tersedia (' . $barang->jumlah_tersedia . ')!';
                return;
            }

            $barang->decrement('jumlah_tersedia', $validated['jumlah']);
            $validated['user_id'] = auth()->id();
            InventarisPeminjaman::create($validated);
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return redirect()->route('admin.inventaris.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil disimpan!');
    }

    public function kembalikanPeminjaman(InventarisPeminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Barang sudah dikembalikan.');
        }

        $peminjaman->inventaris->increment('jumlah_tersedia', $peminjaman->jumlah);
        $peminjaman->update([
            'status'                  => 'dikembalikan',
            'tanggal_kembali_aktual'  => now()->toDateString(),
        ]);

        return back()->with('success', 'Barang berhasil dikembalikan!');
    }

    public function destroyPeminjaman(InventarisPeminjaman $peminjaman)
    {
        // Jika masih dipinjam, kembalikan stok
        if ($peminjaman->status !== 'dikembalikan') {
            $peminjaman->inventaris->increment('jumlah_tersedia', $peminjaman->jumlah);
        }
        $peminjaman->delete();

        return back()->with('success', 'Data peminjaman dihapus!');
    }
}
