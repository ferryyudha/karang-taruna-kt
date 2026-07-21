@extends('public.layouts.app')
@section('title', 'Struktur Anggota — Karang Taruna')

@push('styles')
<style>
    /* Styling Halaman Struktur Organisasi */
    .org-hero {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        padding: 130px 0 80px;
        position: relative;
        overflow: hidden;
        color: white;
    }
    .org-hero::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        top: -200px;
        right: -150px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
        border-radius: 50%;
    }
    
    .pimpinan-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 24px;
        padding: 40px 24px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .pimpinan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.05);
        border-color: #CBD5E1;
    }
    
    .avatar-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        background: #F1F5F9;
        margin-bottom: 20px;
        border: 4px solid #F8FAFC;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .badge-pimpinan {
        color: #4154F1;
        font-weight: 700;
        font-size: 0.72rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: inline-block;
    }
    
    .pimpinan-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0F172A;
        margin-bottom: 6px;
        font-family: 'Poppins', sans-serif;
    }
    
    .pimpinan-period {
        font-size: 0.8rem;
        color: #64748B;
        margin-bottom: 24px;
    }
    
    .pimpinan-social {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: auto;
    }
    .social-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #EEF2FF;
        color: #4154F1;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .social-icon-btn:hover {
        background: #4154F1;
        color: white;
    }

    /* Anggota List Styling */
    .member-grid-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        padding: 28px 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.01);
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .member-grid-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0,0,0,0.04);
        border-color: #CBD5E1;
    }
    .member-avatar-sm {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        overflow: hidden;
        background: #EEF2FF;
        color: #4154F1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 16px;
        border: 3px solid #F8FAFC;
    }
    .member-avatar-sm img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .badge-member {
        background: #F1F5F9;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 8px;
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="org-hero">
    <div class="container" style="position:relative; z-index:3;">
        <div class="row">
            <div class="col-lg-8" data-aos="fade-right">
                <span class="badge mb-3 px-3 py-2 rounded-pill" style="background:#4154F1; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                    STRUKTUR ORGANISASI
                </span>
                <h1 style="font-size:clamp(2.2rem,5vw,3.5rem); font-weight:800; color:white; line-height:1.2; margin-bottom:20px; font-family:'Poppins',sans-serif;">
                    Pilar Pemuda Karang Taruna
                </h1>
                <p style="font-size:1.05rem; color:rgba(255,255,255,0.8); line-height:1.8; max-width:600px; margin-bottom:0;">
                    Bertemu dengan para penggerak di balik setiap inisiatif dan kegiatan Karang Taruna.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Dewan Pimpinan --}}
<section class="section" style="background: #FFFFFF;">
    <div class="container">
        <div class="mb-5" data-aos="fade-up" style="border-left: 4px solid #4154F1; padding-left: 16px;">
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-bottom: 6px; font-family: 'Poppins', sans-serif;">
                Dewan Pimpinan
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Inti dari koordinasi dan strategi organisasi.</p>
        </div>

        @if($anggota->count() > 0)
        @php
            // Filter Dewan Pimpinan (Ketua, Wakil Ketua, Sekretaris, Bendahara)
            $pimpinan = $anggota->filter(function($a) {
                $jab = strtolower($a->jabatan);
                return $jab === 'ketua' || $jab === 'wakil ketua' || $jab === 'sekretaris' || $jab === 'bendahara';
            })->sortBy(function($a) {
                $jab = strtolower($a->jabatan);
                if ($jab === 'ketua') return 1;
                if ($jab === 'wakil ketua') return 2;
                if ($jab === 'sekretaris') return 3;
                return 4; // bendahara
            });

            // Fallback jika kosong
            if ($pimpinan->isEmpty()) {
                $pimpinan = $anggota->take(4);
            }

            // Anggota lainnya
            $anggotaLain = $anggota->diff($pimpinan);
        @endphp

        <div class="row g-4 justify-content-center mb-5">
            @foreach($pimpinan as $p)
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="pimpinan-card">
                    <div class="avatar-wrapper">
                        @if($p->foto)
                            <img src="{{ Storage::url($p->foto) }}" alt="{{ $p->nama }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 w-100" style="background:#EEF2FF; color:#4154F1;">
                                <span style="font-size:2.2rem; font-weight:800;">{{ strtoupper(substr($p->nama,0,1)) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <span class="badge-pimpinan">{{ $p->jabatan }}</span>
                    <h4 class="pimpinan-name">{{ $p->nama }}</h4>
                    <p class="pimpinan-period">{{ $p->periode ?? 'Periode 2024 - 2026' }}</p>
                    
                    <div class="pimpinan-social">
                        @if($p->email)
                        <a href="mailto:{{ $p->email }}" class="social-icon-btn" title="Email {{ $p->nama }}"><i class="bi bi-envelope"></i></a>
                        @endif
                        <a href="#" class="social-icon-btn" title="Bagikan Info" onclick="event.preventDefault(); shareMember('{{ $p->nama }}', '{{ $p->jabatan }}', '{{ $p->periode }}')"><i class="bi bi-share"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Anggota Lainnya --}}
@if(isset($anggotaLain) && $anggotaLain->count() > 0)
<section class="section" style="background: #F8FAFC; border-top: 1px solid #F1F5F9;">
    <div class="container">
        <div class="mb-5" data-aos="fade-up" style="border-left: 4px solid #4154F1; padding-left: 16px;">
            <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin-bottom: 6px; font-family: 'Poppins', sans-serif;">
                Anggota Pengurus
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Anggota dan staf divisi yang aktif berkontribusi.</p>
        </div>

        <div class="row g-4">
            @foreach($anggotaLain as $a)
            <div class="col-sm-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 60 }}">
                <div class="member-grid-card">
                    <div class="member-avatar-sm">
                        @if($a->foto)
                            <img src="{{ Storage::url($a->foto) }}" alt="{{ $a->nama }}">
                        @else
                            {{ strtoupper(substr($a->nama,0,1)) }}{{ isset(explode(' ', $a->nama)[1]) ? strtoupper(substr(explode(' ', $a->nama)[1], 0, 1)) : '' }}
                        @endif
                    </div>
                    
                    <span class="badge-member">{{ $a->jabatan }}</span>
                    <h6 style="font-weight:700; color:#0F172A; font-size:0.95rem; margin-bottom:4px; font-family:'Poppins',sans-serif;">{{ $a->nama }}</h6>
                    <small class="text-muted" style="font-size:0.75rem;">{{ $a->periode ?? 'Periode 2024 - 2026' }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
function shareMember(nama, jabatan, periode) {
    const text = `Pengurus Karang Taruna:\nNama: ${nama}\nJabatan: ${jabatan}\nPeriode: ${periode || '2024 - 2026'}`;
    if (navigator.share) {
        navigator.share({
            title: 'Struktur Pengurus Karang Taruna',
            text: text,
            url: window.location.href
        }).catch(err => console.log('Error sharing:', err));
    } else {
        navigator.clipboard.writeText(text + '\n\nBuka struktur kepengurusan di: ' + window.location.href)
            .then(() => {
                alert('Info pengurus berhasil disalin ke clipboard!');
            })
            .catch(err => {
                console.error('Could not copy text: ', err);
            });
    }
}
</script>
@endpush
@endsection
