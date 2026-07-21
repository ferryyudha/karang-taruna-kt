<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::orderBy('urutan')->paginate(12);
        return view('admin.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
            'periode' => 'nullable|string|max:20',
            'bio'     => 'nullable|string',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:100',
            'urutan'  => 'nullable|integer|min:0',
            'aktif'   => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = ImageUploadService::uploadThumbnail($request->file('foto'), 'anggota');
        }

        $validated['aktif'] = $request->boolean('aktif', true);
        Anggota::create($validated);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function edit(Anggota $anggota)
    {
        return view('admin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
            'periode' => 'nullable|string|max:20',
            'bio'     => 'nullable|string',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:100',
            'urutan'  => 'nullable|integer|min:0',
            'aktif'   => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($anggota->foto) Storage::disk('public')->delete($anggota->foto);
            $validated['foto'] = ImageUploadService::uploadThumbnail($request->file('foto'), 'anggota');
        }

        $validated['aktif'] = $request->boolean('aktif');
        $anggota->update($validated);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil diperbarui!');
    }

    public function destroy(Anggota $anggota)
    {
        if ($anggota->foto) Storage::disk('public')->delete($anggota->foto);
        $anggota->delete();

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil dihapus!');
    }
}
