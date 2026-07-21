<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status'   => 'nullable|in:semua,diterima,diproses,selesai,ditolak',
            'kategori' => 'nullable|string',
            'search'   => 'nullable|string|max:100',
        ]);

        $status   = $request->input('status', 'semua');
        $kategori = $request->input('kategori');
        $search   = $request->input('search');

        $query = Pengaduan::with('petugas');

        if ($status && $status !== 'semua') {
            $query->where('status', $status);
        }

        if ($kategori && array_key_exists($kategori, Pengaduan::$daftarKategori)) {
            $query->where('kategori', $kategori);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_tiket', 'like', '%' . $search . '%')
                  ->orWhere('nama_pelapor', 'like', '%' . $search . '%')
                  ->orWhere('judul', 'like', '%' . $search . '%')
                  ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        $pengaduanList = $query->latest()->paginate(15)->withQueryString();

        // Stat counters
        $totalCount    = Pengaduan::count();
        $diterimaCount = Pengaduan::where('status', 'diterima')->count();
        $diprosesCount = Pengaduan::where('status', 'diproses')->count();
        $selesaiCount  = Pengaduan::where('status', 'selesai')->count();
        $ditolakCount  = Pengaduan::where('status', 'ditolak')->count();

        $daftarKategori = Pengaduan::$daftarKategori;
        $daftarStatus   = Pengaduan::$daftarStatus;

        return view('admin.pengaduan.index', compact(
            'pengaduanList',
            'status',
            'kategori',
            'search',
            'totalCount',
            'diterimaCount',
            'diprosesCount',
            'selesaiCount',
            'ditolakCount',
            'daftarKategori',
            'daftarStatus'
        ));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'status'          => 'required|in:diterima,diproses,selesai,ditolak',
            'tanggapan'       => 'nullable|string',
            'foto_penanganan' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('foto_penanganan')) {
            if ($pengaduan->foto_penanganan) {
                Storage::disk('public')->delete($pengaduan->foto_penanganan);
            }
            $validated['foto_penanganan'] = ImageUploadService::uploadThumbnail($request->file('foto_penanganan'), 'pengaduan');
        }

        $validated['petugas_id'] = auth()->id();

        $pengaduan->update($validated);

        return back()->with('success', "Status laporan [{$pengaduan->kode_tiket}] berhasil diperbarui menjadi " . strtoupper($validated['status']) . "!");
    }

    public function destroy(Pengaduan $pengaduan)
    {
        if ($pengaduan->foto_bukti) {
            Storage::disk('public')->delete($pengaduan->foto_bukti);
        }
        if ($pengaduan->foto_penanganan) {
            Storage::disk('public')->delete($pengaduan->foto_penanganan);
        }

        $pengaduan->delete();

        return back()->with('success', 'Laporan pengaduan berhasil dihapus.');
    }
}
