@extends('public.layouts.app')
@section('title', $kegiatan->nama . ' — Karang Taruna')
@section('content')
{{-- Header --}}
<div style="position:relative;min-height:400px;background:linear-gradient(135deg,#1B2537,#1E3A8A);overflow:hidden;">
    @if($kegiatan->foto_cover)
        <img src="{{ Storage::url($kegiatan->foto_cover) }}" alt="{{ $kegiatan->nama }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:0.35;">
    @endif
    <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,0.3),rgba(15,23,42,0.8));"></div>
    <div class="container" style="position:relative;z-index:2;padding-top:140px;padding-bottom:50px;">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="badge-status badge-status-{{ $kegiatan->status }} mb-3 d-inline-block">{{ $kegiatan->status_label }}</span>
                <h1 style="color:white;font-weight:800;font-size:clamp(1.8rem,5vw,2.8rem);margin-bottom:16px;line-height:1.2;">{{ $kegiatan->nama }}</h1>
                <div style="color:rgba(255,255,255,0.75);font-size:0.9rem;display:flex;align-items:center;justify-content:center;gap:20px;flex-wrap:wrap;">
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $kegiatan->tanggal->format('d F Y') }}</span>
                    @if($kegiatan->lokasi)<span><i class="bi bi-geo-alt me-1"></i>{{ $kegiatan->lokasi }}</span>@endif
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-sm" style="background:#F8FAFC;">
<div class="container">
    <div class="row g-4">
        <div class="col-lg-8">
            @if($kegiatan->deskripsi)
            <div class="card-modern p-4 mb-4" style="background:white;">
                <h5 class="fw-bold mb-3">Tentang Kegiatan</h5>
                <p style="color:#374151;line-height:1.9;font-size:0.95rem;">{!! nl2br(e($kegiatan->deskripsi)) !!}</p>
            </div>
            @endif

            {{-- Gallery --}}
            @if($dokumentasi->count() > 0)
            <div class="card-modern p-4" style="background:white;">
                <h5 class="fw-bold mb-4"><i class="bi bi-images me-2 text-primary"></i>Dokumentasi Kegiatan ({{ $dokumentasi->count() }} Foto)</h5>
                <div class="row g-2">
                    @foreach($dokumentasi as $doc)
                    <div class="col-6 col-md-4">
                        <div style="border-radius:12px;overflow:hidden;aspect-ratio:4/3;cursor:pointer;background:#F1F5F9;"
                             onclick="openLightbox('{{ Storage::url($doc->foto) }}','{{ $doc->keterangan }}')">
                            <img src="{{ Storage::url($doc->foto) }}" alt="{{ $doc->keterangan }}"
                                style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s;"
                                onmouseover="this.style.transform='scale(1.08)'"
                                onmouseout="this.style.transform='scale(1)'">
                        </div>
                        @if($doc->keterangan)
                        <div style="font-size:0.75rem;color:#64748B;padding:4px 2px;">{{ $doc->keterangan }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card-modern p-4 mb-4" style="background:white;">
                <h6 class="fw-bold mb-3">Info Kegiatan</h6>
                <ul class="list-unstyled" style="font-size:0.88rem;color:#64748B;">
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="bi bi-calendar3 text-primary mt-1"></i>
                        <div><div class="fw-semibold text-dark mb-1">Tanggal</div>{{ $kegiatan->tanggal->format('d F Y') }}</div>
                    </li>
                    @if($kegiatan->lokasi)
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt text-primary mt-1"></i>
                        <div><div class="fw-semibold text-dark mb-1">Lokasi</div>{{ $kegiatan->lokasi }}</div>
                    </li>
                    @endif
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="bi bi-bar-chart text-primary mt-1"></i>
                        <div><div class="fw-semibold text-dark mb-1">Status</div><span class="badge-status badge-status-{{ $kegiatan->status }}">{{ $kegiatan->status_label }}</span></div>
                    </li>
                    @if($dokumentasi->count() > 0)
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-images text-primary mt-1"></i>
                        <div><div class="fw-semibold text-dark mb-1">Dokumentasi</div>{{ $dokumentasi->count() }} foto</div>
                    </li>
                    @endif
                </ul>
            </div>

            @if($related->count() > 0)
            <div class="card-modern p-4" style="background:white;">
                <h6 class="fw-bold mb-3">Kegiatan Lainnya</h6>
                @foreach($related as $r)
                <a href="{{ route('public.kegiatan.show', $r) }}" class="text-decoration-none d-flex gap-3 mb-3">
                    <div style="width:60px;height:50px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#E2E8F0;">
                        @if($r->foto_cover)<img src="{{ Storage::url($r->foto_cover) }}" style="width:100%;height:100%;object-fit:cover;" alt="">@else<div class="d-flex align-items-center justify-content-center h-100"><i class="bi bi-calendar2 text-muted"></i></div>@endif
                    </div>
                    <div>
                        <div style="font-size:0.83rem;font-weight:600;color:#1E293B;line-height:1.3;">{{ Str::limit($r->nama, 40) }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">{{ $r->tanggal->format('d M Y') }}</div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    <div class="mt-4"><a href="{{ route('public.kegiatan') }}" style="color:#1E3A8A;font-weight:600;text-decoration:none;"><i class="bi bi-arrow-left me-1"></i>Kembali ke Kegiatan</a></div>
</div>
</section>

{{-- Lightbox --}}
<div id="lightbox" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;align-items:center;justify-content:center;flex-direction:column;">
    <img id="lightboxImg" src="" alt="" style="max-width:90vw;max-height:85vh;border-radius:12px;object-fit:contain;">
    <div id="lightboxCaption" style="color:rgba(255,255,255,0.8);margin-top:12px;font-size:0.9rem;"></div>
    <button onclick="closeLightbox()" style="position:absolute;top:20px;right:24px;background:none;border:none;color:white;font-size:2rem;cursor:pointer;">&times;</button>
</div>
@endsection
@push('scripts')
<script>
function openLightbox(src, caption) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxCaption').textContent = caption;
    const lb = document.getElementById('lightbox');
    lb.style.display = 'flex';
}
function closeLightbox() { document.getElementById('lightbox').style.display = 'none'; }
</script>
@endpush
