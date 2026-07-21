<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::with('user')->latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'isi'      => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'tanggal'  => 'required|date',
            'status'   => 'required|in:publish,draft',
        ]);

        $validated['user_id'] = auth()->id();
        Pengumuman::create($validated);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil ditambahkan!');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'isi'      => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'tanggal'  => 'required|date',
            'status'   => 'required|in:publish,draft',
        ]);

        $pengumuman->update($validated);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}
