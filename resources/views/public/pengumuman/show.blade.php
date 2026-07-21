@extends('public.layouts.app')
@section('title', $pengumuman->judul . ' — Karang Taruna')
@section('content')
<div style="background:linear-gradient(135deg,#1E3A8A,#312E81);padding:120px 0 60px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="badge-kategori mb-3 d-inline-block" style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);">{{ $pengumuman->kategori ?? 'Umum' }}</span>
                <h1 style="color:white;font-weight:800;font-size:clamp(1.5rem,4vw,2.4rem);line-height:1.3;margin-bottom:14px;">{{ $pengumuman->judul }}</h1>
                <div style="color:rgba(255,255,255,0.65);font-size:0.88rem;">
                    <i class="bi bi-calendar3 me-2"></i>{{ $pengumuman->tanggal->format('d F Y') }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-person me-1"></i>{{ $pengumuman->user->name ?? 'Admin' }}
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-sm">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-modern p-4 p-lg-5 mb-4" style="background:white;">
                <div style="font-size:1rem;line-height:1.9;color:#374151;">
                    {!! nl2br(e($pengumuman->isi)) !!}
                </div>
            </div>
            <a href="{{ route('public.pengumuman') }}" style="color:#1E3A8A;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-arrow-left"></i> Kembali ke Pengumuman
            </a>
        </div>
    </div>

    {{-- Related --}}
    @if($related->count() > 0)
    <div class="mt-5">
        <h5 class="fw-bold mb-4">Pengumuman Lainnya</h5>
        <div class="row g-3">
        @foreach($related as $r)
        <div class="col-md-4">
            <div class="card-modern p-3" style="background:white;">
                <span class="badge-kategori mb-2 d-inline-block" style="font-size:0.7rem;">{{ $r->kategori ?? 'Umum' }}</span>
                <h6 style="font-size:0.9rem;font-weight:700;margin-bottom:6px;">{{ Str::limit($r->judul, 50) }}</h6>
                <div style="font-size:0.78rem;color:#94A3B8;margin-bottom:10px;">{{ $r->tanggal->format('d M Y') }}</div>
                <a href="{{ route('public.pengumuman.show', $r) }}" style="font-size:0.82rem;color:#1E3A8A;font-weight:600;text-decoration:none;">Baca <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
        @endforeach
        </div>
    </div>
    @endif
</div>
</section>
@endsection
