@extends('public.layouts.app')
@section('title', 'Karang Taruna — Beranda')
@section('description', 'Karang Taruna - Organisasi kepemudaan aktif dan berdedikasi')

@section('content')
{{-- Hero Section --}}
<section style="min-height:90vh;background-color:#FFFFFF;background-image:radial-gradient(#CBD5E1 1px, transparent 1px);background-size:24px 24px;position:relative;display:flex;align-items:center;overflow:hidden;padding:80px 0;">
    <div class="container" style="position:relative;z-index:2;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="mb-4">
                    <span style="background:#EEF2FF;color:#4F46E5;padding:8px 16px;border-radius:30px;font-size:0.8rem;font-weight:600;letter-spacing:0.5px;display:inline-flex;align-items:center;gap:6px;border:1px solid #E0E7FF;">
                        👋 PEMUDA BERGERAK, BISA MAJU
                    </span>
                </div>
                <h1 style="font-size:clamp(2.5rem,5vw,3.6rem);font-weight:800;color:#0F172A;line-height:1.15;margin-bottom:20px;letter-spacing:-0.5px;">
                    Karang Taruna<br>
                    <span style="color:#4154F1;">Pemuda Indonesia</span>
                </h1>
                <p style="font-size:1.05rem;color:#475569;line-height:1.8;max-width:520px;margin-bottom:36px;">
                    Wadah kreativitas dan aksi nyata pemuda untuk membangun lingkungan yang aktif, inovatif, dan harmonis. Bersama kita ciptakan perubahan positif bagi masyarakat.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('public.pengaduan') }}" class="btn btn-warning" style="padding:14px 28px;border-radius:12px;font-size:0.95rem;text-decoration:none;font-weight:700;display:inline-flex;align-items:center;gap:8px;color:#0F172A;box-shadow:0 6px 20px rgba(245,158,11,0.35);">
                        <i class="bi bi-megaphone-fill"></i> Lapor Pengaduan
                    </a>
                    <a href="{{ route('public.kegiatan') }}" class="btn-primary-custom" style="padding:14px 28px;border-radius:12px;font-size:0.95rem;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:8px;">
                        Lihat Kegiatan <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="{{ route('public.lomba') }}" class="btn btn-outline-primary" style="padding:14px 28px;border-radius:12px;font-size:0.95rem;font-weight:600;border-color:#E2E8F0;color:#0F172A;display:inline-flex;align-items:center;gap:8px;background:white;">
                        <i class="bi bi-trophy"></i> Lomba
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-center align-items-center" data-aos="fade-left" data-aos-delay="200">
                <div style="position:relative;padding:10px;">
                    {{-- Decorative shadow behind image --}}
                    <div style="position:absolute;inset:0;background:rgba(65,84,241,0.08);border-radius:24px;transform:rotate(-3deg);z-index:1;"></div>
                    <img src="{{ asset('images/youth_meeting.png') }}" alt="Karang Taruna Collaboration" style="position:relative;z-index:2;width:100%;max-width:500px;border-radius:24px;box-shadow:0 20px 40px rgba(0,0,0,0.08);transform:rotate(2deg);transition:all 0.3s;object-fit:cover;aspect-ratio:4/3;" onmouseover="this.style.transform='rotate(0deg)'" onmouseout="this.style.transform='rotate(2deg)'">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats Bar --}}
<section style="padding:32px 0;background:#F8FAFC;border-bottom:1px solid #F1F5F9;border-top:1px solid #F1F5F9;">
    <div class="container">
        <div class="row justify-content-center align-items-center gap-4 gap-md-5">
            <div class="col-md-auto col-5 d-flex align-items-center gap-3 justify-content-center" data-aos="fade-up">
                <div style="width:48px;height:48px;border-radius:12px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;color:#4154F1;">
                    <i class="bi bi-people-fill" style="font-size:1.25rem;"></i>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#0F172A;font-family:'Poppins',sans-serif;line-height:1.2;">{{ $stats['anggota'] }}+</div>
                    <div style="font-size:0.82rem;color:#64748B;font-weight:500;white-space:nowrap;">Anggota Aktif</div>
                </div>
            </div>
            <div class="col-md-auto d-none d-md-block" style="width:1px;height:40px;background:#E2E8F0;"></div>
            <div class="col-md-auto col-5 d-flex align-items-center gap-3 justify-content-center" data-aos="fade-up" data-aos-delay="100">
                <div style="width:48px;height:48px;border-radius:12px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;color:#4154F1;">
                    <i class="bi bi-calendar-check-fill" style="font-size:1.25rem;"></i>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#0F172A;font-family:'Poppins',sans-serif;line-height:1.2;">{{ $stats['kegiatan'] }}+</div>
                    <div style="font-size:0.82rem;color:#64748B;font-weight:500;white-space:nowrap;">Kegiatan Rutin</div>
                </div>
            </div>
            <div class="col-md-auto d-none d-md-block" style="width:1px;height:40px;background:#E2E8F0;"></div>
            <div class="col-md-auto col-5 d-flex align-items-center gap-3 justify-content-center" data-aos="fade-up" data-aos-delay="200">
                <div style="width:48px;height:48px;border-radius:12px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;color:#4154F1;">
                    <i class="bi bi-megaphone-fill" style="font-size:1.3rem;"></i>
                </div>
                <div>
                    <div style="font-size:1.5rem;font-weight:800;color:#0F172A;font-family:'Poppins',sans-serif;line-height:1.2;">{{ $stats['pengaduan'] }}+</div>
                    <div style="font-size:0.82rem;color:#64748B;font-weight:500;white-space:nowrap;">Laporan Pengaduan</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Banner Pengaduan Warga --}}
<section class="section" style="background: linear-gradient(135deg, #1E3A8A, #4154F1); color: white; padding: 50px 0;">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8 text-center text-lg-start" data-aos="fade-right">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold mb-2" style="font-size:0.82rem;">
                    <i class="bi bi-shield-exclamation me-1"></i>Layanan Publik Karang Taruna
                </span>
                <h3 class="fw-bold mb-2 text-white" style="font-family:'Poppins',sans-serif;">Punya Masalah Lingkungan? Laporkan Di Sini!</h3>
                <p class="text-white-50 mb-0" style="font-size:0.95rem;">
                    Jalan rusak, sampah menumpuk, drainase tersumbat, atau penerangan jalan mati? Laporkan pengaduan Anda tanpa perlu login dan pantau proses penanganannya secara transparan via Kode Tiket.
                </p>
            </div>
            <div class="col-lg-4 text-center text-lg-end" data-aos="fade-left">
                <a href="{{ route('public.pengaduan') }}" class="btn btn-warning btn-lg rounded-3 fw-bold text-dark px-4 py-3" style="font-size:1rem; box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);">
                    <i class="bi bi-megaphone-fill me-2"></i>Buat Pengaduan Warga
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Pengumuman Terbaru --}}
<section class="section" style="background:#FFFFFF;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-up">
            <div>
                <h2 class="section-title mb-1" style="font-family:'Poppins',sans-serif;">Pengumuman Terbaru</h2>
                <p class="text-muted mb-0" style="font-size:0.92rem;">Informasi terkini mengenai agenda dan program kami.</p>
            </div>
            <a href="{{ route('public.pengumuman') }}" class="text-decoration-none fw-semibold" style="color:#4154F1;font-size:0.9rem;display:inline-flex;align-items:center;gap:4px;">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-4">
            @forelse($pengumuman->take(3) as $p)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="card-modern" style="background:#FFFFFF;border-radius:20px;height:100%;">
                    <div style="padding:28px;display:flex;flex-direction:column;height:100%;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge-kategori">{{ $p->kategori ?? 'Umum' }}</span>
                            <span style="font-size:0.78rem;color:#94A3B8;"><i class="bi bi-calendar3 me-1"></i>{{ $p->tanggal->format('d M Y') }}</span>
                        </div>
                        <h5 style="font-size:1.05rem;font-weight:700;color:#0F172A;margin-bottom:12px;line-height:1.4;">{{ $p->judul }}</h5>
                        <p style="font-size:0.88rem;color:#475569;line-height:1.7;margin-bottom:20px;flex:1;">{{ Str::limit(strip_tags($p->isi), 110) }}</p>
                        <a href="{{ route('public.pengumuman.show', $p) }}" style="color:#4154F1;font-weight:600;font-size:0.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                            Baca selengkapnya <i class="bi bi-chevron-right" style="font-size:0.75rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12" data-aos="fade-up">
                <div class="text-center py-5" style="border:2px dashed #E2E8F0;border-radius:20px;background:#F8FAFC;padding:40px;">
                    <div style="width:56px;height:56px;border-radius:50%;background:#F1F5F9;display:inline-flex;align-items:center;justify-content:center;color:#94A3B8;margin-bottom:16px;">
                        <i class="bi bi-megaphone-mute" style="font-size:1.5rem;"></i>
                    </div>
                    <h6 style="font-weight:700;color:#0F172A;margin-bottom:6px;font-size:1rem;">Belum ada pengumuman</h6>
                    <p class="text-muted mb-0" style="font-size:0.85rem;max-width:380px;margin:0 auto;line-height:1.6;">Pastikan untuk mengecek halaman ini secara berkala untuk update terbaru dari Karang Taruna.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Kegiatan Terkini --}}
<section class="section" style="background:#F8FAFC;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title mb-2">Kegiatan Terkini</h2>
            <p class="text-muted" style="font-size:0.95rem;">Berpartisipasi dalam berbagai aktivitas seru yang kami selenggarakan.</p>
        </div>

        @if($kegiatan->count() > 0)
            @php $featured = $kegiatan->first(); @endphp
            {{-- Large Horizontal Featured Kegiatan --}}
            <div class="card-modern mb-5" style="background:#FFFFFF;border-radius:24px;border:1px solid #E2E8F0;box-shadow:0 10px 30px rgba(0,0,0,0.02);" data-aos="fade-up">
                <div class="row g-0">
                    <div class="col-lg-6" style="min-height:280px;position:relative;">
                        @if($featured->foto_cover)
                            <img src="{{ Storage::url($featured->foto_cover) }}" alt="{{ $featured->nama }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                        @else
                            <div style="background:linear-gradient(135deg,#1E3A8A,#4154F1);width:100%;height:100%;display:flex;align-items:center;justify-content:center;position:absolute;inset:0;">
                                <i class="bi bi-calendar-event text-white-50" style="font-size:5rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge-status badge-status-{{ $featured->status }}">{{ $featured->status_label }}</span>
                            <span class="text-muted" style="font-size:0.85rem;"><i class="bi bi-calendar3 me-1"></i>{{ $featured->tanggal->format('d M Y') }}</span>
                        </div>
                        <h3 style="font-weight:800;color:#0F172A;font-size:1.45rem;margin-bottom:14px;line-height:1.35;">{{ $featured->nama }}</h3>
                        <p style="color:#475569;font-size:0.92rem;line-height:1.7;margin-bottom:24px;">
                            {{ Str::limit(strip_tags($featured->deskripsi ?? 'Ikuti keseruan agenda kegiatan kami bersama seluruh pemuda dan warga desa.'), 160) }}
                        </p>
                        
                        @if($featured->lokasi)
                        <div class="d-flex align-items-center gap-2 mb-4" style="font-size:0.88rem; color:#475569;">
                            <i class="bi bi-geo-alt-fill text-primary" style="font-size:1.1rem;"></i>
                            <span>{{ $featured->lokasi }}</span>
                        </div>
                        @endif

                        <div>
                            <a href="{{ route('public.kegiatan.show', $featured) }}" class="btn-primary-custom" style="padding:11px 26px;border-radius:10px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                                Ikut Kegiatan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Remaining Kegiatan Grid (if any) --}}
            @if($kegiatan->count() > 1)
            <div class="row g-4">
                @foreach($kegiatan->skip(1)->take(2) as $k)
                <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="card-modern" style="background:#FFFFFF;border-radius:20px;height:100%;">
                        <div class="row g-0 h-100">
                            <div class="col-sm-5" style="min-height:150px;position:relative;">
                                @if($k->foto_cover)
                                    <img src="{{ Storage::url($k->foto_cover) }}" alt="{{ $k->nama }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                                @else
                                    <div style="background:linear-gradient(135deg,#3B82F6,#7C3AED);width:100%;height:100%;display:flex;align-items:center;justify-content:center;position:absolute;inset:0;">
                                        <i class="bi bi-calendar-event text-white-50" style="font-size:3rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sm-7 p-4 d-flex flex-column justify-content-center">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge-status badge-status-{{ $k->status }}" style="font-size:0.68rem;padding:3px 8px;">{{ $k->status_label }}</span>
                                    <span class="text-muted" style="font-size:0.75rem;">{{ $k->tanggal->format('d M Y') }}</span>
                                </div>
                                <h5 style="font-weight:700;color:#0F172A;font-size:0.95rem;margin-bottom:8px;line-height:1.4;">{{ $k->nama }}</h5>
                                <p style="color:#64748B;font-size:0.8rem;line-height:1.6;margin-bottom:12px;" class="flex-grow-1">
                                    {{ Str::limit(strip_tags($k->deskripsi ?? ''), 80) }}
                                </p>
                                <a href="{{ route('public.kegiatan.show', $k) }}" style="color:#4154F1;font-weight:600;font-size:0.8rem;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                    Detail Kegiatan <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        @else
            <div class="text-center text-muted py-5">Belum ada kegiatan.</div>
        @endif
    </div>
</section>

{{-- Pengurus Section --}}
<section class="section" style="background:#FFFFFF;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5" data-aos="fade-up">
            <div>
                <h2 class="section-title mb-1">Kenali Pengurus Kami</h2>
                <p class="text-muted mb-0" style="font-size:0.92rem;">Tim di balik setiap pergerakan dan inovasi Karang Taruna.</p>
            </div>
            <a href="{{ route('public.anggota') }}" class="btn btn-outline-primary" style="border-radius:10px;padding:10px 22px;font-size:0.88rem;font-weight:600;color:#4154F1;border-color:#E2E8F0;background:white;">
                Lihat Struktur Anggota
            </a>
        </div>
        
        <div class="row g-4 justify-content-center">
            @php
            // Ambil core pengurus (Ketua, Sekretaris, Bendahara, Humas dll)
            $corePengurus = $anggota->filter(function($a) {
                $jabatan = strtolower($a->jabatan);
                return str_contains($jabatan, 'ketua') || str_contains($jabatan, 'sekretaris') || str_contains($jabatan, 'bendahara') || str_contains($jabatan, 'humas');
            })->take(4);

            // Fallback jika kosong, ambil 4 anggota pertama
            if($corePengurus->isEmpty()) {
                $corePengurus = $anggota->take(4);
            }
            
            // Definisikan warna lingkaran agar bervariasi menyerupai screenshot
            $avatarColors = ['#1E3A8A', '#6366F1', '#475569', '#EEF2FF'];
            $textColors = ['#FFFFFF', '#FFFFFF', '#FFFFFF', '#4154F1'];
            @endphp

            @forelse($corePengurus as $idx => $p)
            @php 
                $bg = $avatarColors[$idx % count($avatarColors)];
                $txt = $textColors[$idx % count($textColors)];
            @endphp
            <div class="col-md-3 col-sm-6 text-center" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    {{-- Circle Avatar --}}
                    <div style="width:130px;height:130px;border-radius:50%;background:{{ $bg }};display:flex;align-items:center;justify-content:center;color:{{ $txt }};font-size:2.2rem;font-weight:700;margin-bottom:18px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.04);border:3px solid #FFFFFF;">
                        @if($p->foto)
                            <img src="{{ Storage::url($p->foto) }}" alt="{{ $p->nama }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr($p->nama, 0, 1)) }}{{ isset(explode(' ', $p->nama)[1]) ? strtoupper(substr(explode(' ', $p->nama)[1], 0, 1)) : '' }}
                        @endif
                    </div>
                    <h5 style="font-size:0.98rem;font-weight:700;color:#0F172A;margin-bottom:4px;">{{ $p->nama }}</h5>
                    <p style="font-size:0.75rem;color:#64748B;font-weight:600;letter-spacing:1px;text-transform:uppercase;">{{ $p->jabatan }}</p>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted">Belum ada data pengurus.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
