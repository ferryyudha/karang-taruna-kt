@extends('public.layouts.app')
@section('title', 'Galeri — Karang Taruna')

@push('styles')
<style>
    /* Styling Halaman Galeri */
    .galeri-hero {
        background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 50%, #312E81 100%);
        padding: 140px 0 80px;
        position: relative;
        overflow: hidden;
        color: white;
    }
    .galeri-hero::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        top: -100px;
        right: -100px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
        border-radius: 50%;
    }
    
    /* Filter Bar */
    .filter-btn {
        background: #F1F5F9;
        color: #475569;
        border: none;
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 0.88rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .filter-btn.active {
        background: #1E3A8A;
        color: white;
    }
    .filter-btn:hover:not(.active) {
        background: #E2E8F0;
        color: #0F172A;
    }
    
    /* Gallery Item Cards */
    .gallery-item-card {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        background: #E2E8F0;
        width: 100%;
        transition: all 0.3s ease;
    }
    .gallery-item-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
        display: block;
    }
    .gallery-item-card:hover img {
        transform: scale(1.05);
    }
    .gallery-item-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.1) 75%, transparent 100%);
        z-index: 2;
    }
    .gallery-item-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 24px;
        z-index: 3;
        color: white;
    }
    .gallery-item-overlay h4 {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 4px;
        font-family: 'Poppins', sans-serif;
    }
    .gallery-item-overlay p {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.7);
        margin-bottom: 0;
    }
    
    /* Layout Sizes */
    .large-vertical {
        height: 520px;
    }
    .horizontal {
        height: 248px;
    }
    .square {
        height: 248px;
    }
    .normal {
        height: 280px;
    }
    
    @media(max-width: 991px) {
        .large-vertical, .horizontal, .square, .normal {
            height: 280px;
        }
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="galeri-hero">
    <div class="container" style="position:relative; z-index:3;">
        <div class="row">
            <div class="col-lg-8" data-aos="fade-right">
                <span class="badge mb-3 px-3 py-2 rounded-pill" style="background:#4154F1; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                    MOMEN BERHARGA
                </span>
                <h1 style="font-size:clamp(2.2rem,5vw,3.5rem); font-weight:800; color:white; line-height:1.2; margin-bottom:20px; font-family:'Poppins',sans-serif;">
                    Galeri Kegiatan Karang Taruna
                </h1>
                <p style="font-size:1.05rem; color:rgba(255,255,255,0.8); line-height:1.8; max-width:600px; margin-bottom:0;">
                    Kumpulan jejak langkah dan kebersamaan pemuda dalam membangun lingkungan yang lebih baik.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Gallery Section --}}
@php
    $allPhotos = collect();
    foreach($kegiatanDenganFoto as $k) {
        $nama = strtolower($k->nama);
        $kategori = 'Kegiatan Sosial';
        if (str_contains($nama, 'bersih') || str_contains($nama, 'lingkungan') || str_contains($nama, 'sampah') || str_contains($nama, 'pohon')) {
            $kategori = 'Lingkungan';
        } elseif (str_contains($nama, 'futsal') || str_contains($nama, 'turnamen') || str_contains($nama, 'cup') || str_contains($nama, 'olahraga') || str_contains($nama, 'lomba') || str_contains($nama, 'badminton')) {
            $kategori = 'Olahraga';
        } elseif (str_contains($nama, 'seni') || str_contains($nama, 'budaya') || str_contains($nama, 'pentas') || str_contains($nama, 'festival') || str_contains($nama, 'kuliner')) {
            $kategori = 'Seni & Budaya';
        }
        
        foreach($k->dokumentasi as $d) {
            $allPhotos->push([
                'id' => $d->id,
                'foto' => $d->foto,
                'keterangan' => $d->keterangan,
                'kegiatan_nama' => $k->nama,
                'kegiatan_tanggal' => $k->tanggal,
                'kegiatan_id' => $k->id,
                'kategori' => $kategori
            ]);
        }
    }
@endphp

<section class="section" style="background:#FFFFFF;">
    <div class="container">
        {{-- Filter & Count Row --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-5" data-aos="fade-up">
            <div class="d-flex flex-wrap gap-2">
                <button class="filter-btn active" data-filter="all">Semua</button>
                <button class="filter-btn" data-filter="Kegiatan Sosial">Kegiatan Sosial</button>
                <button class="filter-btn" data-filter="Olahraga">Olahraga</button>
                <button class="filter-btn" data-filter="Seni & Budaya">Seni & Budaya</button>
                <button class="filter-btn" data-filter="Lingkungan">Lingkungan</button>
            </div>
            <div class="text-muted" style="font-size:0.9rem; font-weight:500;">
                <i class="bi bi-images me-1"></i> <span id="photoCount">{{ $allPhotos->count() }}</span> Foto ditemukan
            </div>
        </div>

        @if($allPhotos->count() > 0)
        {{-- Asymmetric Masonry Row --}}
        <div class="row g-4" id="galleryGrid">
            {{-- Card 1: Large Vertical --}}
            @if($allPhotos->has(0))
            @php $f = $allPhotos->get(0); @endphp
            <div class="col-lg-5 col-md-6 gallery-item" data-category="{{ $f['kategori'] }}" data-aos="fade-up">
                <div class="gallery-item-card large-vertical" onclick="openLightbox('{{ Storage::url($f['foto']) }}','{{ $f['kegiatan_nama'] }} - {{ $f['keterangan'] }}')">
                    <img src="{{ Storage::url($f['foto']) }}" alt="{{ $f['keterangan'] }}">
                    <div class="gallery-item-overlay">
                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-2" style="font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">{{ $f['kategori'] }}</span>
                        <h4>{{ $f['kegiatan_nama'] }}</h4>
                        <p>{{ $f['keterangan'] }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Right container for 2, 3, 4 --}}
            <div class="col-lg-7 col-md-6 d-flex flex-column gap-4">
                <div class="row g-4">
                    {{-- Card 2: Horizontal --}}
                    @if($allPhotos->has(1))
                    @php $f = $allPhotos->get(1); @endphp
                    <div class="col-12 gallery-item" data-category="{{ $f['kategori'] }}" data-aos="fade-up">
                        <div class="gallery-item-card horizontal" onclick="openLightbox('{{ Storage::url($f['foto']) }}','{{ $f['kegiatan_nama'] }} - {{ $f['keterangan'] }}')">
                            <img src="{{ Storage::url($f['foto']) }}" alt="{{ $f['keterangan'] }}">
                            <div class="gallery-item-overlay">
                                <span class="badge bg-primary px-3 py-2 rounded-pill mb-2" style="font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">{{ $f['kategori'] }}</span>
                                <h4>{{ $f['kegiatan_nama'] }}</h4>
                                <p>{{ $f['keterangan'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Card 3: Square --}}
                    @if($allPhotos->has(2))
                    @php $f = $allPhotos->get(2); @endphp
                    <div class="col-sm-6 gallery-item" data-category="{{ $f['kategori'] }}" data-aos="fade-up">
                        <div class="gallery-item-card square" onclick="openLightbox('{{ Storage::url($f['foto']) }}','{{ $f['kegiatan_nama'] }} - {{ $f['keterangan'] }}')">
                            <img src="{{ Storage::url($f['foto']) }}" alt="{{ $f['keterangan'] }}">
                            <div class="gallery-item-overlay">
                                <span class="badge bg-primary px-2 py-1 rounded-pill mb-2" style="font-size:0.6rem; font-weight:700; text-transform:uppercase;">{{ $f['kategori'] }}</span>
                                <h4>{{ $f['kegiatan_nama'] }}</h4>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Card 4: Square --}}
                    @if($allPhotos->has(3))
                    @php $f = $allPhotos->get(3); @endphp
                    <div class="col-sm-6 gallery-item" data-category="{{ $f['kategori'] }}" data-aos="fade-up">
                        <div class="gallery-item-card square" onclick="openLightbox('{{ Storage::url($f['foto']) }}','{{ $f['kegiatan_nama'] }} - {{ $f['keterangan'] }}')">
                            <img src="{{ Storage::url($f['foto']) }}" alt="{{ $f['keterangan'] }}">
                            <div class="gallery-item-overlay">
                                <span class="badge bg-primary px-2 py-1 rounded-pill mb-2" style="font-size:0.6rem; font-weight:700; text-transform:uppercase;">{{ $f['kategori'] }}</span>
                                <h4>{{ $f['kegiatan_nama'] }}</h4>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Row for subsequent cards (3 columns) --}}
        @if($allPhotos->count() > 4)
        <div class="row g-4 mt-2">
            @foreach($allPhotos->skip(4) as $f)
            <div class="col-md-6 col-lg-4 gallery-item" data-category="{{ $f['kategori'] }}" data-aos="fade-up">
                <div class="gallery-item-card normal" onclick="openLightbox('{{ Storage::url($f['foto']) }}','{{ $f['kegiatan_nama'] }} - {{ $f['keterangan'] }}')">
                    <img src="{{ Storage::url($f['foto']) }}" alt="{{ $f['keterangan'] }}">
                    <div class="gallery-item-overlay">
                        <span class="badge bg-primary px-2 py-1 rounded-pill mb-2" style="font-size:0.6rem; font-weight:700; text-transform:uppercase;">{{ $f['kategori'] }}</span>
                        <h4>{{ $f['kegiatan_nama'] }}</h4>
                        <p>{{ $f['keterangan'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        
        {{-- Load more button placeholder --}}
        <div class="text-center mt-5" data-aos="fade-up">
            <button class="btn btn-outline-primary px-4 py-2" style="border-radius:12px; font-weight:600; color:#1E3A8A; border-color:#E2E8F0; background:white;">
                Lihat Lebih Banyak <i class="bi bi-chevron-down ms-1"></i>
            </button>
        </div>
        @else
        <div class="text-center py-5 text-muted" style="border:2px dashed #E2E8F0; border-radius:20px; background:#F8FAFC;">
            <i class="bi bi-images d-block mb-2" style="font-size:3.5rem; opacity:0.3; color:#94A3B8;"></i>
            Belum ada dokumentasi kegiatan.
        </div>
        @endif
    </div>
</section>

{{-- Lightbox --}}
<div id="lightbox" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:9999;align-items:center;justify-content:center;flex-direction:column;">
    <img id="lightboxImg" src="" alt="" style="max-width:90vw;max-height:85vh;border-radius:12px;object-fit:contain;">
    <div id="lightboxCaption" style="color:rgba(255,255,255,0.7);margin-top:12px;font-size:0.88rem;text-align:center;max-width:60vw;"></div>
    <button onclick="closeLightbox()" style="position:absolute;top:20px;right:24px;background:none;border:none;color:white;font-size:2.5rem;cursor:pointer;line-height:1;">&times;</button>
</div>
@endsection

@push('scripts')
<script>
function openLightbox(src, cap) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxCaption').textContent = cap;
    const lb = document.getElementById('lightbox');
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeLightbox(); });

// Client-side filtering logic
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    const photoCountSpan = document.getElementById('photoCount');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Toggle active class on buttons
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');
            let visibleCount = 0;

            galleryItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                
                if (filterValue === 'all' || itemCategory === filterValue) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (photoCountSpan) {
                photoCountSpan.textContent = visibleCount;
            }
        });
    });
});
</script>
@endpush
