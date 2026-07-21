<?php

namespace App\Http\Controllers;

use App\Models\Polling;
use App\Models\PollingOpsi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PollingController extends Controller
{
    public function index(Request $request)
    {
        $query = Polling::withCount(['votes', 'opsi'])
            ->with('pembuatBy')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $daftarPolling = $query->paginate(12)->withQueryString();
        return view('admin.polling.index', compact('daftarPolling'));
    }

    public function create()
    {
        return view('admin.polling.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'tipe'          => 'required|in:single,multi',
            'mulai_at'      => 'required|date',
            'selesai_at'    => 'required|date|after:mulai_at',
            'status'        => 'required|in:draft,aktif',
            'tampil_publik' => 'nullable|boolean',
            'opsi'          => 'required|array|min:2',
            'opsi.*'        => 'required|string|max:255',
        ]);

        $polling = Polling::create([
            'judul'         => $validated['judul'],
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'tipe'          => $validated['tipe'],
            'mulai_at'      => $validated['mulai_at'],
            'selesai_at'    => $validated['selesai_at'],
            'status'        => $validated['status'],
            'tampil_publik' => $request->boolean('tampil_publik'),
            'dibuat_oleh'   => auth()->id(),
        ]);

        foreach ($validated['opsi'] as $i => $teks) {
            if (trim($teks) !== '') {
                PollingOpsi::create([
                    'polling_id' => $polling->id,
                    'teks_opsi'  => trim($teks),
                    'urutan'     => $i + 1,
                ]);
            }
        }

        return redirect()->route('admin.polling.index')
            ->with('success', "Polling \"{$polling->judul}\" berhasil dibuat!");
    }

    public function show(Polling $polling)
    {
        return redirect()->route('admin.polling.hasil', $polling);
    }

    public function edit(Polling $polling)
    {
        $polling->load('opsi');
        $hasVotes = $polling->votes()->exists();
        return view('admin.polling.edit', compact('polling', 'hasVotes'));
    }

    public function update(Request $request, Polling $polling)
    {
        $hasVotes = $polling->votes()->exists();

        $validated = $request->validate([
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'nullable|string',
            'tipe'          => ['required', Rule::in(['single', 'multi'])],
            'mulai_at'      => 'required|date',
            'selesai_at'    => 'required|date|after:mulai_at',
            'status'        => ['required', Rule::in(['draft', 'aktif', 'selesai'])],
            'tampil_publik' => 'nullable|boolean',
            // Opsi hanya wajib jika belum ada vote
            'opsi'          => $hasVotes ? 'nullable|array' : 'required|array|min:2',
            'opsi.*'        => 'nullable|string|max:255',
            // Opsi baru (tambah saat edit walau sudah ada vote)
            'opsi_baru'     => 'nullable|array',
            'opsi_baru.*'   => 'nullable|string|max:255',
        ]);

        $polling->update([
            'judul'         => $validated['judul'],
            'deskripsi'     => $validated['deskripsi'] ?? null,
            'tipe'          => $validated['tipe'],
            'mulai_at'      => $validated['mulai_at'],
            'selesai_at'    => $validated['selesai_at'],
            'status'        => $validated['status'],
            'tampil_publik' => $request->boolean('tampil_publik'),
        ]);

        // Update opsi yang sudah ada (hanya jika belum ada vote)
        if (!$hasVotes && !empty($validated['opsi'])) {
            // Hapus opsi lama yang tidak termasuk
            $keptIds = collect($request->opsi_id ?? [])->filter()->values();
            $polling->opsi()->whereNotIn('id', $keptIds)->delete();

            foreach ($request->opsi_id ?? [] as $i => $opsiId) {
                $teks = $validated['opsi'][$i] ?? null;
                if ($opsiId && $teks) {
                    PollingOpsi::where('id', $opsiId)
                        ->where('polling_id', $polling->id)
                        ->update(['teks_opsi' => trim($teks), 'urutan' => $i + 1]);
                }
            }
        }

        // Tambah opsi baru (selalu boleh, meski sudah ada vote)
        $maxUrutan = $polling->opsi()->max('urutan') ?? 0;
        foreach ($request->input('opsi_baru', []) as $i => $teks) {
            if (trim($teks ?? '') !== '') {
                PollingOpsi::create([
                    'polling_id' => $polling->id,
                    'teks_opsi'  => trim($teks),
                    'urutan'     => $maxUrutan + $i + 1,
                ]);
            }
        }

        return redirect()->route('admin.polling.index')
            ->with('success', "Polling \"{$polling->judul}\" berhasil diperbarui!");
    }

    public function destroy(Polling $polling)
    {
        $judul = $polling->judul;
        $polling->delete();
        return redirect()->route('admin.polling.index')
            ->with('success', "Polling \"{$judul}\" berhasil dihapus.");
    }

    public function hasil(Polling $polling)
    {
        $polling->load(['opsi.votes', 'votes', 'pembuatBy']);

        $totalVotes = $polling->votes()->count();
        $totalVoter = $polling->votes()->distinct('user_id')->count('user_id');

        return view('admin.polling.hasil', compact('polling', 'totalVotes', 'totalVoter'));
    }
}
