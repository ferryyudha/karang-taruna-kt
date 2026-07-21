@extends('public.layouts.app')
@section('title', 'Pengumuman — Karang Taruna')

@push('styles')
<style>
    /* Transisi perpindahan layout */
    #announcementsContainer {
        transition: all 0.3s ease-in-out;
    }
    .announcement-item {
        transition: all 0.3s ease-in-out;
    }
    
    /* Styling List View (Aktif via JS) */
    .list-view-active .announcement-item {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }
    .list-view-active .card-modern {
        border-radius: 16px !important;
    }
    .list-view-active .card-modern-inner {
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 24px !important;
        flex-wrap: wrap;
    }
    .list-view-active .card-modern-inner .meta-container {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 120px;
    }
    .list-view-active .card-modern-inner .meta-container .mb-4 {
        margin-bottom: 0 !important;
    }
    .list-view-active .card-modern-inner .content-container {
        flex: 1;
        min-width: 280px;
    }
    .list-view-active .card-modern-inner .content-container p {
        margin-bottom: 0 !important;
    }
    .list-view-active .card-modern-inner .link-container {
        min-width: 140px;
        text-align: right;
    }

    @media(max-width: 768px) {
        .list-view-active .card-modern-inner {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 16px !important;
        }
        .list-view-active .card-modern-inner .link-container {
            text-align: left;
            min-width: auto;
        }
    }
</style>
@endpush

@section('content')
<section style="padding: 130px 0 80px; background: #FFFFFF;">
    <div class="container">
        {{-- Header Section --}}
        <div class="mb-5" data-aos="fade-up">
            <span style="font-size: 0.75rem; font-weight: 700; color: #4154F1; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 8px;">
                UPDATE TERBARU
            </span>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: #0F172A; margin-bottom: 12px; font-family: 'Poppins', sans-serif;">
                Pengumuman
            </h1>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <p class="text-muted mb-0" style="font-size: 0.95rem; max-width: 600px; line-height: 1.6;">
                    Informasi resmi, berita terkini, dan agenda mendesak dari pengurus Karang Taruna untuk seluruh anggota komunitas.
                </p>
                {{-- Grid/List layout toggle --}}
                <div class="d-flex align-items-center gap-2" style="background: #F1F5F9; padding: 4px; border-radius: 8px;">
                    <button id="btnGridView" class="btn btn-sm shadow-sm" style="background: white; border: none; padding: 6px 12px; border-radius: 6px; color: #4154F1; transition: all 0.25s;">
                        <i class="bi bi-grid-fill"></i>
                    </button>
                    <button id="btnListView" class="btn btn-sm text-muted" style="border: none; padding: 6px 12px; border-radius: 6px; transition: all 0.25s; background: transparent;">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Grid of announcements --}}
        <div id="announcementsContainer" class="row g-4">
            @forelse($pengumuman as $p)
            @php
                $kat = strtolower($p->kategori ?? 'umum');
                $badgeBg = match($kat) {
                    'penting' => '#4F46E5',
                    'sosial' => '#1E3A8A',
                    'olahraga' => '#334155',
                    default => '#4154F1',
                };
            @endphp
            <div class="col-md-6 col-lg-4 announcement-item" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 60 }}">
                <div class="card-modern" style="background:#FFFFFF; border-radius:20px; border:1px solid #E2E8F0; height:100%; display:flex; flex-direction:column;">
                    <div class="card-modern-inner" style="padding:28px; flex:1; display:flex; flex-direction:column; height:100%;">
                        <div class="meta-container">
                            <div class="mb-4">
                                <span style="background:{{ $badgeBg }}; color:#FFFFFF; padding:5px 12px; border-radius:20px; font-size:0.7rem; font-weight:700; letter-spacing:0.5px; text-transform:uppercase;">
                                    {{ $p->kategori ?? 'Umum' }}
                                </span>
                            </div>
                            
                            <div style="font-size:0.8rem; color:#64748B; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                                <i class="bi bi-calendar3" style="color:#94A3B8;"></i>
                                <span>{{ $p->tanggal->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="content-container">
                            <h5 style="font-size:1.08rem; font-weight:700; color:#0F172A; margin-bottom:12px; line-height:1.45; font-family:'Poppins',sans-serif;">
                                {{ $p->judul }}
                            </h5>
                            
                            <p style="font-size:0.88rem; color:#475569; line-height:1.7; margin-bottom:20px;">
                                {{ Str::limit(strip_tags($p->isi), 120) }}
                            </p>
                        </div>
                        
                        <div class="link-container">
                            <a href="{{ route('public.pengumuman.show', $p) }}" style="color:#4154F1; font-weight:600; font-size:0.85rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition: gap 0.2s;" onmouseover="this.style.gap='10px'" onmouseout="this.style.gap='6px'">
                                Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12" data-aos="fade-up">
                <div class="text-center py-5" style="border:2px dashed #E2E8F0; border-radius:20px; background:#F8FAFC; padding:40px;">
                    <div style="width:56px; height:56px; border-radius:50%; background:#F1F5F9; display:inline-flex; align-items:center; justify-content:center; color:#94A3B8; margin-bottom:16px;">
                        <i class="bi bi-megaphone-mute" style="font-size:1.5rem;"></i>
                    </div>
                    <h6 style="font-weight:700; color:#0F172A; margin-bottom:6px; font-size:1rem;">Belum ada pengumuman</h6>
                    <p class="text-muted mb-0" style="font-size:0.85rem; max-width:380px; margin:0 auto; line-height:1.6;">Pastikan untuk mengecek halaman ini secara berkala untuk update terbaru dari Karang Taruna.</p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($pengumuman->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $pengumuman->links() }}
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnGridView = document.getElementById('btnGridView');
        const btnListView = document.getElementById('btnListView');
        const container = document.getElementById('announcementsContainer');

        if (btnGridView && btnListView && container) {
            btnGridView.addEventListener('click', function() {
                // Atur button style
                btnGridView.classList.add('shadow-sm');
                btnGridView.style.background = 'white';
                btnGridView.style.color = '#4154F1';

                btnListView.classList.remove('shadow-sm');
                btnListView.style.background = 'transparent';
                btnListView.style.color = '#64748B';

                // Ubah layout container
                container.classList.remove('list-view-active');
            });

            btnListView.addEventListener('click', function() {
                // Atur button style
                btnListView.classList.add('shadow-sm');
                btnListView.style.background = 'white';
                btnListView.style.color = '#4154F1';

                btnGridView.classList.remove('shadow-sm');
                btnGridView.style.background = 'transparent';
                btnGridView.style.color = '#64748B';

                // Ubah layout container
                container.classList.add('list-view-active');
            });
        }
    });
</script>
@endpush
