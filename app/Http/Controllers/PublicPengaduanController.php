<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class PublicPengaduanController extends Controller
{
    public function index(Request $request)
    {
        $ticketCode = trim($request->input('tiket', ''));
        $trackResult = null;

        if ($ticketCode) {
            $trackResult = Pengaduan::where('kode_tiket', $ticketCode)->first();
        }

        $recentPengaduan = Pengaduan::latest()->take(10)->get();
        $daftarKategori  = Pengaduan::$daftarKategori;
        $daftarStatus    = Pengaduan::$daftarStatus;

        return view('public.pengaduan.index', compact(
            'trackResult',
            'ticketCode',
            'recentPengaduan',
            'daftarKategori',
            'daftarStatus'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor'  => 'required|string|max:100',
            'phone_pelapor' => 'required|string|max:30',
            'email_pelapor' => 'nullable|email|max:100',
            'kategori'      => 'required|in:jalan_rusak,sampah,drainase,lampu_jalan,keamanan,lainnya',
            'lokasi'        => 'required|string|max:255',
            'judul'         => 'required|string|max:150',
            'isi_laporan'   => 'required|string|min:10',
            'foto_bukti'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = ImageUploadService::uploadThumbnail($request->file('foto_bukti'), 'pengaduan');
        }

        $validated['kode_tiket'] = Pengaduan::generateKodeTiket();
        $validated['status']     = 'diterima';

        $pengaduan = Pengaduan::create($validated);

        return redirect()->route('public.pengaduan', ['tiket' => $pengaduan->kode_tiket])
            ->with('success_tiket', $pengaduan->kode_tiket);
    }
}
