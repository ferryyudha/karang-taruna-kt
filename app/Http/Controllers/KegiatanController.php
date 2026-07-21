<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with('user')->latest()->paginate(10);
        return view('admin.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('admin.kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'tanggal'    => 'required|date',
            'lokasi'     => 'nullable|string|max:255',
            'foto_cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
        ]);

        if ($request->hasFile('foto_cover')) {
            $validated['foto_cover'] = ImageUploadService::uploadPhoto($request->file('foto_cover'), 'kegiatan');
        }

        $validated['user_id'] = auth()->id();
        Kegiatan::create($validated);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'tanggal'    => 'required|date',
            'lokasi'     => 'nullable|string|max:255',
            'foto_cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
        ]);

        if ($request->hasFile('foto_cover')) {
            if ($kegiatan->foto_cover) {
                Storage::disk('public')->delete($kegiatan->foto_cover);
            }
            $validated['foto_cover'] = ImageUploadService::uploadPhoto($request->file('foto_cover'), 'kegiatan');
        }

        $kegiatan->update($validated);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui!');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        // Hapus dulu file-file foto dokumentasi terkait dari storage.
        // Baris datanya sendiri akan ikut terhapus otomatis (cascade) dari database,
        // tapi file fisiknya TIDAK otomatis terhapus kalau tidak dilakukan manual di sini.
        foreach ($kegiatan->dokumentasi as $dok) {
            if ($dok->foto) {
                Storage::disk('public')->delete($dok->foto);
            }
        }

        if ($kegiatan->foto_cover) {
            Storage::disk('public')->delete($kegiatan->foto_cover);
        }
        $kegiatan->delete();
        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }
}
