@extends('public.layouts.app')
@section('title', 'Lomba — Karang Taruna')

@push('styles')
<style>
    .lomba-hero {
        background: linear-gradient(135deg, #0F172A 0%, #7C3AED 55%, #4154F1 100%);
        padding: 140px 0 80px;
        position: relative;
        overflow: hidden;
    }
    .lomba-hero::before {
        content: '';
        position: absolute;
        width: 480px; height: 480px;
        top: -120px; right: -80px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.18), transparent 70%);
        border-radius: 50%;
    }

    .lomba-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        transition: all 0.3s ease;
    }
    .lomba-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }
    .lomba-card-body { padding: 26px; }

    .badge-lomba-kategori {
        background: #F5F3FF; color: #7C3AED;
        font-weight: 700; font-size: 0.68rem;
        padding: 5px 12px; border-radius: 20px;
        text-transform: uppercase; letter-spacing: 0.5px;
        display: inline-block;
    }
    .badge-status-persiapan  { background: #EFF6FF; color: #2563EB; }
    .badge-status-berlangsung { background: #FFFBEB; color: #D97706; }
    .badge-status-selesai    { background: #F0FDF4; color: #16A34A; }
    .badge-lomba-status {
        padding: 5px 12px; border-radius: 20px;
        font-size: 0.7rem; font-weight: 700;
    }

    .podium-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 16px;
        border-radius: 14px;
        background: #F8FAFC;
        margin-bottom: 8px;
    }
    .podium-rank {
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 0.85rem; flex-shrink: 0;
        color: white;
    }
    .rank-1 { background: linear-gradient(135deg,#F59E0B,#D97706); }
    .rank-2 { background: linear-gradient(135deg,#94A3B8,#64748B); }
    .rank-3 { background: linear-gradient(135deg,#B45309,#92400E); }
    .rank-other { background: #CBD5E1; color: #475569; }

    .empty-box {
        text-align: center; padding: 60px 20px; color: #94A3B8;
        border: 2px dashed #E2E8F0; border-radius: 20px; background: #F8FAFC;
    }
</style>
@endpush

@section('content')
{{-- Hero --}}
<section class="lomba-hero">
    <div class="container" style="position:relative; z-index:4;">
        <div class="row">
            <div class="col-lg-8" data-aos="fade-right">
                <span style="font-size:0.75rem; font-weight:700; color:#FBBF24; letter-spacing:1px; text-transform:uppercase; display:block; margin-bottom:8px;">
                    SEMANGAT KOMPETISI
                </span>
                <h1 style="font-size:clamp(2.2rem,5vw,3.5rem); font-weight:800; color:white; line-height:1.2; margin-bottom:20px; font-family:'Poppins',sans-serif;">
                    Lomba & Perlombaan
                </h1>
                <p style="font-size:1.05rem; color:rgba(255,255,255,0.85); line-height:1.8; max-width:600px; margin-bottom:0;">
                    Ikuti berbagai lomba seru dari Karang Taruna — mulai dari lomba tradisional 17 Agustusan sampai kompetisi olahraga antar warga.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Mendatang / Berlangsung --}}
<section class="section" style="background: #FFFFFF;">
    <div class="container">
        <div class="mb-4" data-aos="fade-up">
            <h2 class="section-title mb-1" style="font-family:'Poppins',sans-serif;">Lomba Mendatang &amp; Berlangsung</h2>
            <p class="text-muted mb-0" style="font-size:0.92rem;">Catat jadwalnya dan siap-siap ikut ramaikan!</p>
        </div>

        @if($mendatang->count() > 0)
        <div class="row g-4">
            @foreach($mendatang as $l)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="lomba-card">
                    <div class="lomba-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            @if($l->kategori)
                                <span class="badge-lomba-kategori">{{ $l->kategori }}</span>
                            @else
                                <span></span>
                            @endif
                            <span class="badge-lomba-status badge-status-{{ $l->status }}">{{ $l->status_label }}</span>
                        </div>

                        <h5 style="font-size:1.08rem; font-weight:700; color:#0F172A; margin-bottom:10px; line-height:1.4; font-family:'Poppins',sans-serif;">
                            {{ $l->nama }}
                        </h5>

                        <div style="font-size:0.8rem; color:#64748B; margin-bottom:6px;">
                            <i class="bi bi-collection me-1" style="color:#94A3B8;"></i>{{ $l->kegiatan->nama }}
                        </div>
                        <div style="font-size:0.8rem; color:#64748B; margin-bottom:6px;">
                            <i class="bi bi-calendar3 me-1" style="color:#94A3B8;"></i>{{ $l->tanggal->format('d M Y') }}
                            @if($l->waktu_mulai)
                                &nbsp;·&nbsp;<i class="bi bi-clock me-1" style="color:#94A3B8;"></i>{{ \Carbon\Carbon::parse($l->waktu_mulai)->format('H:i') }}
                            @endif
                        </div>
                        @if($l->lokasi)
                        <div style="font-size:0.8rem; color:#64748B;">
                            <i class="bi bi-geo-alt-fill me-1" style="color:#7C3AED;"></i>{{ $l->lokasi }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-box" data-aos="fade-up">
            <i class="bi bi-trophy d-block mb-2" style="font-size:3rem; opacity:0.3;"></i>
            Belum ada lomba yang dijadwalkan dalam waktu dekat.
        </div>
        @endif
    </div>
</section>

{{-- Selesai + Pemenang --}}
<section class="section" style="background: #F8FAFC; border-top:1px solid #F1F5F9;">
    <div class="container">
        <div class="mb-5" data-aos="fade-up">
            <h2 class="section-title mb-1" style="font-family:'Poppins',sans-serif;">Hasil Lomba</h2>
            <p class="text-muted mb-0" style="font-size:0.92rem;">Selamat kepada seluruh pemenang!</p>
        </div>

        @if($selesai->count() > 0)
        <div class="row g-4">
            @foreach($selesai as $l)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="lomba-card">
                    <div class="lomba-card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            @if($l->kategori)
                                <span class="badge-lomba-kategori">{{ $l->kategori }}</span>
                            @else
                                <span></span>
                            @endif
                            <span class="badge-lomba-status badge-status-selesai">Selesai</span>
                        </div>

                        <h5 style="font-size:1.05rem; font-weight:700; color:#0F172A; margin-bottom:6px; line-height:1.4; font-family:'Poppins',sans-serif;">
                            {{ $l->nama }}
                        </h5>
                        <div style="font-size:0.78rem; color:#64748B; margin-bottom:16px;">
                            <i class="bi bi-calendar3 me-1"></i>{{ $l->tanggal->format('d M Y') }} · {{ $l->kegiatan->nama }}
                        </div>

                        @if($l->pemenang->count() > 0)
                        <div>
                            @foreach($l->pemenang as $index => $p)
                            <div class="podium-item">
                                <div class="podium-rank {{ $index === 0 ? 'rank-1' : ($index === 1 ? 'rank-2' : ($index === 2 ? 'rank-3' : 'rank-other')) }}">
                                    <i class="bi bi-trophy-fill" style="font-size:0.75rem;"></i>
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-weight:700; font-size:0.85rem; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->nama_peserta }}</div>
                                    <div style="font-size:0.72rem; color:#64748B;">{{ $p->juara }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-muted" style="font-size:0.8rem;">Hasil belum diumumkan.</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($selesai->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $selesai->links() }}
        </div>
        @endif
        @else
        <div class="empty-box" data-aos="fade-up">
            <i class="bi bi-trophy d-block mb-2" style="font-size:3rem; opacity:0.3;"></i>
            Belum ada lomba yang selesai dilaksanakan.
        </div>
        @endif
    </div>
</section>
@endsection
