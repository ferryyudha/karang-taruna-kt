<?php

namespace App\Http\Controllers;

use App\Models\Polling;
use App\Models\PollingVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggotaPollingController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Polling aktif yang belum divote
        $pollingAktif = Polling::with('opsi')
            ->where('status', 'aktif')
            ->where('mulai_at', '<=', now())
            ->where('selesai_at', '>=', now())
            ->whereDoesntHave('votes', fn($q) => $q->where('user_id', $userId))
            ->latest('mulai_at')
            ->get();

        // Polling yang sudah pernah divote oleh user ini
        $pollingDiikuti = Polling::with(['opsi.votes', 'votes'])
            ->whereHas('votes', fn($q) => $q->where('user_id', $userId))
            ->latest()
            ->get();

        // Polling selesai publik yang belum diikuti (untuk ditampilkan hasilnya)
        $pollingSelesai = Polling::with(['opsi.votes', 'votes'])
            ->where('status', 'selesai')
            ->whereDoesntHave('votes', fn($q) => $q->where('user_id', $userId))
            ->latest()
            ->take(6)
            ->get();

        return view('anggota.polling.index', compact('pollingAktif', 'pollingDiikuti', 'pollingSelesai', 'userId'));
    }

    public function show(Polling $polling)
    {
        $userId = auth()->id();
        $polling->load(['opsi.votes', 'votes']);

        $sudahVote = $polling->sudahDivoteOleh($userId);
        $isAktif   = $polling->is_aktif;

        // Opsi yang dipilih user (untuk tampilkan jika sudah vote)
        $pilihanUser = $sudahVote
            ? $polling->votes()->where('user_id', $userId)->pluck('polling_opsi_id')
            : collect();

        $totalVotes = $polling->votes()->count();

        return view('anggota.polling.show', compact(
            'polling', 'sudahVote', 'isAktif', 'pilihanUser', 'totalVotes', 'userId'
        ));
    }

    public function vote(Request $request, Polling $polling)
    {
        $userId = auth()->id();

        // Validasi polling masih aktif
        if (!$polling->is_aktif) {
            return back()->with('error', 'Polling ini tidak aktif atau sudah berakhir.');
        }

        // Cek sudah pernah vote
        if ($polling->sudahDivoteOleh($userId)) {
            return back()->with('error', 'Anda sudah pernah memberikan suara pada polling ini.');
        }

        // Validasi opsi
        $rules = $polling->tipe === 'single'
            ? ['opsi_id'   => 'required|integer|exists:polling_opsi,id']
            : ['opsi_id'   => 'required|array|min:1', 'opsi_id.*' => 'integer|exists:polling_opsi,id'];

        $validated = $request->validate($rules);

        $opsiIds = is_array($validated['opsi_id'])
            ? $validated['opsi_id']
            : [$validated['opsi_id']];

        // Pastikan semua opsi milik polling ini
        $validOpsiIds = $polling->opsi()->pluck('id')->toArray();
        foreach ($opsiIds as $opsiId) {
            if (!in_array($opsiId, $validOpsiIds)) {
                return back()->with('error', 'Opsi pilihan tidak valid.');
            }
        }

        // Simpan vote
        DB::transaction(function () use ($polling, $userId, $opsiIds) {
            foreach ($opsiIds as $opsiId) {
                PollingVote::create([
                    'polling_id'      => $polling->id,
                    'polling_opsi_id' => $opsiId,
                    'user_id'         => $userId,
                ]);
            }
        });

        return redirect()->route('anggota.polling.show', $polling)
            ->with('success', 'Suara Anda berhasil dicatat! Terima kasih sudah berpartisipasi.');
    }
}
