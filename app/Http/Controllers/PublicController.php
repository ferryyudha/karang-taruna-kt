<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Models\Pengumuman;
use App\Models\Lomba;

class PublicController extends Controller
{
    public function home()
    {
        $pengumuman = Pengumuman::published()->latest()->take(3)->get();
        $kegiatan   = Kegiatan::latest()->take(3)->get();
        $anggota    = Anggota::where('aktif', true)->orderBy('urutan')->take(6)->get();

        $stats = [
            'anggota'   => Anggota::where('aktif', true)->count(),
            'kegiatan'  => Kegiatan::count(),
            'pengaduan' => \App\Models\Pengaduan::count(),
            'tahun'     => date('Y') - 2010, // Adjust founding year
        ];

        return view('public.home', compact('pengumuman', 'kegiatan', 'anggota', 'stats'));
    }

    public function pengumumanIndex()
    {
        $pengumuman = Pengumuman::published()->latest()->paginate(9);
        return view('public.pengumuman.index', compact('pengumuman'));
    }

    public function pengumumanShow(Pengumuman $pengumuman)
    {
        abort_if($pengumuman->status !== 'publish', 404);
        $related = Pengumuman::published()
            ->where('id', '!=', $pengumuman->id)
            ->latest()->take(3)->get();
        return view('public.pengumuman.show', compact('pengumuman', 'related'));
    }

    public function kegiatanIndex()
    {
        $today = now()->toDateString();
        $upcoming  = Kegiatan::where('tanggal', '>=', $today)->orderBy('tanggal')->get();
        $completed = Kegiatan::where('tanggal', '<', $today)->latest('tanggal')->paginate(6);
        return view('public.kegiatan.index', compact('upcoming', 'completed'));
    }

    public function kegiatanShow(Kegiatan $kegiatan)
    {
        $dokumentasi = $kegiatan->dokumentasi()->latest()->get();
        $related = Kegiatan::where('id', '!=', $kegiatan->id)->latest()->take(3)->get();
        return view('public.kegiatan.show', compact('kegiatan', 'dokumentasi', 'related'));
    }

    public function anggota()
    {
        $anggota = Anggota::where('aktif', true)->orderBy('urutan')->get();
        return view('public.anggota', compact('anggota'));
    }

    public function galeri()
    {
        $kegiatanDenganFoto = Kegiatan::has('dokumentasi')
            ->with(['dokumentasi' => fn($q) => $q->latest()])
            ->latest('tanggal')->paginate(6);
        $allDokumentasi = Dokumentasi::with('kegiatan')->latest()->take(24)->get();
        return view('public.galeri', compact('kegiatanDenganFoto', 'allDokumentasi'));
    }

    public function lombaIndex()
    {
        $today = now()->toDateString();

        // Mendatang/Berlangsung: tanggal >= hari ini, diurutkan dari yang paling dekat
        $mendatang = Lomba::with('kegiatan')
            ->where('tanggal', '>=', $today)
            ->orderBy('tanggal')
            ->get();

        // Selesai: tanggal < hari ini — tampilkan beserta pemenang
        $selesai = Lomba::with(['kegiatan', 'pemenang'])
            ->where('tanggal', '<', $today)
            ->latest('tanggal')
            ->paginate(6);

        return view('public.lomba.index', compact('mendatang', 'selesai'));
    }

    public function kalenderIndex()
    {
        return redirect()->route('public.kegiatan', ['view' => 'calendar']);
    }

    public function kalenderEvents(\Illuminate\Http\Request $request)
    {
        $start = $request->input('start');
        $end   = $request->input('end');

        // 1. Fetch Kegiatan
        $kegiatanQuery = Kegiatan::query();
        if ($start && $end) {
            $kegiatanQuery->whereBetween('tanggal', [$start, $end]);
        }
        $kegiatan = $kegiatanQuery->get();

        // 2. Fetch Lomba
        $lombaQuery = Lomba::with('kegiatan');
        if ($start && $end) {
            $lombaQuery->whereBetween('tanggal', [$start, $end]);
        }
        $lomba = $lombaQuery->get();

        $events = [];

        foreach ($kegiatan as $k) {
            $events[] = [
                'id'              => 'kegiatan-' . $k->id,
                'title'           => '📍 ' . $k->nama,
                'start'           => $k->tanggal ? \Carbon\Carbon::parse($k->tanggal)->format('Y-m-d') : null,
                'url'             => route('public.kegiatan.show', $k),
                'backgroundColor' => '#4154F1',
                'borderColor'     => '#3143D9',
                'textColor'       => '#FFFFFF',
                'extendedProps'   => [
                    'type'        => 'kegiatan',
                    'badge'       => 'Kegiatan Taruna',
                    'lokasi'      => $k->lokasi ?? 'Lokasi belum ditentukan',
                    'deskripsi'   => \Illuminate\Support\Str::limit(strip_tags($k->deskripsi ?? ''), 120),
                    'statusLabel' => $k->status_label,
                    'detailUrl'   => route('public.kegiatan.show', $k),
                ],
            ];
        }

        foreach ($lomba as $l) {
            $events[] = [
                'id'              => 'lomba-' . $l->id,
                'title'           => '🏆 ' . $l->nama,
                'start'           => $l->tanggal ? \Carbon\Carbon::parse($l->tanggal)->format('Y-m-d') : null,
                'url'             => route('public.lomba'),
                'backgroundColor' => '#F59E0B',
                'borderColor'     => '#D97706',
                'textColor'       => '#0F172A',
                'extendedProps'   => [
                    'type'        => 'lomba',
                    'badge'       => 'Perlombaan',
                    'kategori'    => $l->kategori ?? 'Umum',
                    'waktu'       => $l->waktu_mulai ?? '-',
                    'lokasi'      => $l->lokasi ?? 'Lokasi belum ditentukan',
                    'deskripsi'   => \Illuminate\Support\Str::limit(strip_tags($l->deskripsi ?? ''), 120),
                    'detailUrl'   => route('public.lomba'),
                ],
            ];
        }

        return response()->json($events);
    }
}
