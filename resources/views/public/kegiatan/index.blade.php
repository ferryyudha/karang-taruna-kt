@extends('public.layouts.app')
@section('title', 'Kegiatan & Agenda — Karang Taruna')
@section('description', 'Jadwal terpadu seluruh kegiatan, agenda sosial, dan perlombaan Karang Taruna.')

@push('styles')
<style>
    /* Styling khusus halaman Kegiatan */
    .kegiatan-hero {
        background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 50%, #312E81 100%);
        padding: 130px 0 70px;
        position: relative;
        overflow: hidden;
    }
    .kegiatan-hero::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        top: -100px;
        right: -100px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
        border-radius: 50%;
    }
    .kegiatan-hero::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        bottom: -50px;
        left: -50px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.12), transparent 70%);
        border-radius: 50%;
    }
    
    .btn-slide-nav {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid #E2E8F0;
        background: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        transition: all 0.25s;
        text-decoration: none;
    }
    .btn-slide-nav:hover {
        background: #F1F5F9;
        color: #4154F1;
        border-color: #CBD5E1;
    }
    
    .kegiatan-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px rgba(0,0,0,0.01);
        transition: all 0.3s ease;
    }
    .kegiatan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }
    
    .badge-kegiatan {
        background: #E0E7FF;
        color: #4338CA;
        font-weight: 700;
        font-size: 0.68rem;
        padding: 5px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    
    .featured-completed-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        min-height: 420px;
        height: 100%;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    }
    .featured-completed-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
        z-index: 1;
        transition: transform 0.5s ease;
    }
    .featured-completed-card:hover img {
        transform: scale(1.04);
    }
    .featured-completed-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.4) 60%, transparent 100%);
        z-index: 2;
    }
    .featured-completed-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 40px;
        z-index: 3;
    }
    
    .small-completed-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 20px 24px;
        transition: all 0.25s ease;
        text-decoration: none;
        display: block;
        box-shadow: 0 2px 10px rgba(0,0,0,0.01);
    }
    .small-completed-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        border-color: #CBD5E1;
    }

    /* FullCalendar Styling */
    .fc { font-family: 'Inter', sans-serif; }
    .fc .fc-toolbar-title { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.35rem; color: #0F172A; }
    .fc .fc-button-primary { background-color: #4154F1; border-color: #4154F1; border-radius: 10px; font-weight: 600; font-size: 0.85rem; padding: 8px 16px; text-transform: capitalize; }
    .fc .fc-button-primary:hover, .fc .fc-button-primary:focus { background-color: #3143D9 !important; border-color: #3143D9 !important; box-shadow: none !important; }
    .fc-theme-standard td, .fc-theme-standard th { border-color: #F1F5F9; }
    .fc-theme-standard .fc-scrollgrid { border-color: #E2E8F0; border-radius: 16px; overflow: hidden; }
    .fc-daygrid-day-number { font-weight: 600; font-size: 0.85rem; color: #475569; padding: 8px !important; }
    .fc-event { cursor: pointer; border-radius: 8px !important; padding: 4px 8px !important; font-size: 0.82rem !important; font-weight: 600 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.06); transition: transform 0.15s ease; }
    .fc-event:hover { transform: scale(1.02); }
    
    .filter-btn {
        padding: 8px 18px; border-radius: 20px; font-size: 0.88rem; font-weight: 600; border: 1px solid #E2E8F0; background: white; color: #475569; transition: all 0.2s; cursor: pointer;
    }
    .filter-btn.active { background: #4154F1; color: white; border-color: #4154F1; box-shadow: 0 4px 12px rgba(65,84,241,0.25); }

    .mode-tab-btn {
        border-radius: 12px; padding: 10px 22px; font-weight: 700; font-size: 0.9rem; transition: all 0.25s; cursor: pointer;
    }
    .mode-tab-btn.active { background: #FFFFFF; color: #1E3A8A; box-shadow: 0 4px 15px rgba(0,0,0,0.15); border: none; }
    .mode-tab-btn.inactive { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.85); border: 1px solid rgba(255,255,255,0.2); }
    .mode-tab-btn.inactive:hover { background: rgba(255,255,255,0.2); color: white; }

    /* ====== MOBILE FIXES ====== */
    @media (max-width: 767px) {
        .kegiatan-hero { padding: 90px 0 50px; }

        /* View switcher: stack vertically on phone */
        .mode-switcher-wrap {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }
        .mode-tab-btn {
            width: 100%;
            text-align: center;
            padding: 12px 16px;
        }

        /* Featured card shorter on mobile */
        .featured-completed-card { min-height: 280px; }
        .featured-completed-info { padding: 24px; }
        .featured-completed-info h3 { font-size: 1.2rem !important; }

        /* Kegiatan card: reduce padding */
        .kegiatan-card > div:last-child { padding: 20px !important; }
    }

    /* FullCalendar Mobile Toolbar Fix */
    @media (max-width: 575px) {
        .fc .fc-toolbar { flex-wrap: wrap; gap: 6px; }
        .fc .fc-toolbar-chunk { width: 100%; display: flex; justify-content: center; }
        .fc .fc-toolbar-title { font-size: 1rem !important; }
        .fc .fc-button { font-size: 0.78rem !important; padding: 6px 10px !important; }
        .fc .fc-button-group { gap: 2px; }
        /* On very small phones, hide week view button */
        .fc-timeGridWeek-button { display: none !important; }
    }

    @media (max-width: 767px) {
        /* Calendar card: remove excess padding */
        .fc-card-body-pad { padding: 12px !important; }
        .fc .fc-toolbar-title { font-size: 1.1rem; }
        .fc .fc-button-primary { font-size: 0.8rem !important; padding: 6px 12px !important; }
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="kegiatan-hero">
    <div class="container" style="position:relative; z-index:4;">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <span style="font-size:0.75rem; font-weight:700; color:#60A5FA; letter-spacing:1px; text-transform:uppercase; display:block; margin-bottom:8px;">
                    AGENDA & KEGIATAN TARUNA
                </span>
                <h1 style="font-size:clamp(2.2rem,5vw,3.3rem); font-weight:800; color:white; line-height:1.2; margin-bottom:16px; font-family:'Poppins',sans-serif;">
                    Aksi Nyata Pemuda<br>Untuk Masyarakat.
                </h1>
                <p style="font-size:1.02rem; color:rgba(255,255,255,0.85); line-height:1.8; max-width:600px; margin-bottom:24px;">
                    Ikuti berbagai kegiatan sosial, olahraga, dan pengembangan diri bersama Karang Taruna. Pilih tampilan Kalender atau Daftar Kartu di bawah ini.
                </p>
                
                {{-- View Mode Switcher --}}
                <div class="mode-switcher-wrap d-inline-flex flex-wrap p-1 rounded-3 gap-2" style="background:rgba(15,23,42,0.4); border:1px solid rgba(255,255,255,0.15);">
                    <button type="button" id="btnViewCalendar" class="mode-tab-btn active" onclick="switchMode('calendar')">
                        <i class="bi bi-calendar3 me-2"></i>Tampilan Kalender
                    </button>
                    <button type="button" id="btnViewCard" class="mode-tab-btn inactive" onclick="switchMode('card')">
                        <i class="bi bi-grid-fill me-2"></i>Tampilan Daftar Kartu
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== MODE 1: CALENDAR VIEW ==================== --}}
<div id="calendarViewSection" class="section" style="background: #F8FAFC; min-height:70vh;">
    <div class="container">
        {{-- Card Controls & Legend --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">
                    {{-- Filter Buttons --}}
                    <div class="col-md-7 d-flex flex-wrap align-items-center gap-2">
                        <span class="fw-bold text-dark me-2" style="font-size:0.9rem;"><i class="bi bi-funnel me-1"></i>Filter Agenda:</span>
                        <button type="button" class="filter-btn active" onclick="filterEvents('all', this)">
                            ✨ Semua Agenda
                        </button>
                        <button type="button" class="filter-btn" onclick="filterEvents('kegiatan', this)">
                            📍 Kegiatan Taruna
                        </button>
                        <button type="button" class="filter-btn" onclick="filterEvents('lomba', this)">
                            🏆 Perlombaan
                        </button>
                    </div>

                    {{-- Legend Badges --}}
                    <div class="col-md-5 d-flex justify-content-md-end align-items-center gap-3" style="font-size:0.85rem;">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:14px;height:14px;border-radius:4px;background:#4154F1;display:inline-block;"></span>
                            <span class="fw-semibold text-slate-700">Kegiatan Taruna</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:14px;height:14px;border-radius:4px;background:#F59E0B;display:inline-block;"></span>
                            <span class="fw-semibold text-slate-700">Perlombaan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FullCalendar Render Area --}}
        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body p-4 fc-card-body-pad">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODE 2: CARD LIST VIEW ==================== --}}
<div id="cardViewSection" style="display: none;">
    {{-- Upcoming --}}
    <section class="section" style="background: #FFFFFF;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4" data-aos="fade-up">
                <div>
                    <h2 class="section-title mb-1" style="font-family:'Poppins',sans-serif;">Kegiatan Akan Datang</h2>
                    <p class="text-muted mb-0" style="font-size:0.92rem;">Daftarkan dirimu dan berkontribusi langsung.</p>
                </div>
            </div>

            @if($upcoming->count() > 0)
            <div class="row g-4">
                @foreach($upcoming as $k)
                @php
                    $nama = strtolower($k->nama);
                    $kategori = 'Sosial';
                    if (str_contains($nama, 'bersih') || str_contains($nama, 'lingkungan') || str_contains($nama, 'sampah') || str_contains($nama, 'pohon')) {
                        $kategori = 'Lingkungan';
                    } elseif (str_contains($nama, 'futsal') || str_contains($nama, 'turnamen') || str_contains($nama, 'cup') || str_contains($nama, 'olahraga') || str_contains($nama, 'lomba')) {
                        $kategori = 'Olahraga';
                    } elseif (str_contains($nama, 'pelatihan') || str_contains($nama, 'workshop') || str_contains($nama, 'digital') || str_contains($nama, 'belajar')) {
                        $kategori = 'Workshop';
                    }
                @endphp
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="kegiatan-card">
                        <div style="height:200px; background:#E2E8F0; overflow:hidden; position:relative;">
                            @if($k->foto_cover)
                                <img src="{{ Storage::url($k->foto_cover) }}" alt="{{ $k->nama }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <div style="background:linear-gradient(135deg,#1E3A8A,#4154F1); width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                                    <i class="bi bi-calendar-event text-white-50" style="font-size:3.5rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div style="padding:28px; flex:1; display:flex; flex-direction:column;">
                            <div class="mb-3">
                                <span class="badge-kegiatan">{{ $kategori }}</span>
                            </div>
                            
                            <div style="font-size:0.8rem; color:#64748B; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                                <i class="bi bi-calendar3" style="color:#94A3B8;"></i>
                                <span>{{ $k->tanggal->format('d M Y') }}</span>
                            </div>

                            <h5 style="font-size:1.08rem; font-weight:700; color:#0F172A; margin-bottom:12px; line-height:1.45; font-family:'Poppins',sans-serif;">
                                {{ $k->nama }}
                            </h5>
                            
                            <p style="font-size:0.88rem; color:#475569; line-height:1.7; margin-bottom:24px; flex:1;">
                                {{ Str::limit(strip_tags($k->deskripsi ?? 'Ayo berpartisipasi dan ramaikan kegiatan Karang Taruna demi mempererat tali silaturahmi.'), 100) }}
                            </p>
                            
                            @if($k->lokasi)
                            <div style="font-size:0.82rem; color:#64748B; margin-bottom:20px; display:flex; align-items:center; gap:6px;">
                                <i class="bi bi-geo-alt-fill text-primary"></i>
                                <span>{{ $k->lokasi }}</span>
                            </div>
                            @endif

                            <a href="{{ route('public.kegiatan.show', $k) }}" class="btn-primary-custom text-center text-decoration-none" style="background:#1E3A8A; padding:11px; border-radius:10px;">
                                Detail & Daftar
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5 text-muted" style="border:2px dashed #E2E8F0; border-radius:20px; background:#F8FAFC;">
                <i class="bi bi-calendar-event-fill d-block mb-2" style="font-size:3rem; opacity:0.3; color:#94A3B8;"></i>
                Belum ada kegiatan yang direncanakan dalam waktu dekat.
            </div>
            @endif
        </div>
    </section>

    {{-- Completed --}}
    <section class="section" style="background: #F8FAFC; border-top:1px solid #F1F5F9;">
        <div class="container">
            <div class="mb-5" data-aos="fade-up">
                <h2 class="section-title mb-1" style="font-family:'Poppins',sans-serif;">Kegiatan Selesai</h2>
                <p class="text-muted mb-0" style="font-size:0.92rem;">Melihat kembali momen kebersamaan dan dampak yang telah kita buat.</p>
            </div>

            @if($completed->count() > 0)
            <div class="row g-4">
                {{-- Left Column: Large Featured Card --}}
                @php $featured = $completed->first(); @endphp
                <div class="col-lg-7" data-aos="fade-right">
                    @php
                        $namaF = strtolower($featured->nama);
                        $katF = 'Sosial';
                        if (str_contains($namaF, 'kemerdekaan') || str_contains($namaF, 'ri') || str_contains($namaF, 'hut')) {
                            $katF = 'Perayaan Besar';
                        } elseif (str_contains($namaF, 'bersih') || str_contains($namaF, 'lingkungan')) {
                            $katF = 'Lingkungan';
                        }
                    @endphp
                    <a href="{{ route('public.kegiatan.show', $featured) }}" class="text-decoration-none d-block h-100">
                        <div class="featured-completed-card">
                            @if($featured->foto_cover)
                                <img src="{{ Storage::url($featured->foto_cover) }}" alt="{{ $featured->nama }}">
                            @else
                                <div style="background:linear-gradient(135deg,#3B82F6,#1E3A8A); width:100%; height:100%; position:absolute; inset:0;"></div>
                            @endif
                            <div class="featured-completed-info">
                                <span class="badge bg-primary px-3 py-2 mb-3 rounded-pill" style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">
                                    {{ $katF }}
                                </span>
                                <h3 style="font-weight:800; font-size:1.6rem; line-height:1.3; margin-bottom:12px; font-family:'Poppins',sans-serif;">
                                    {{ $featured->nama }}
                                </h3>
                                <p style="color:rgba(255,255,255,0.8); font-size:0.9rem; line-height:1.6; margin-bottom:24px;">
                                    {{ Str::limit(strip_tags($featured->deskripsi ?? 'Dokumentasi kegiatan dan momen kebersamaan yang telah sukses diselenggarakan.'), 140) }}
                                </p>
                                <div class="d-flex align-items-center gap-3" style="font-size:0.8rem; color:rgba(255,255,255,0.7);">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $featured->tanggal->format('d M Y') }}</span>
                                    @if($featured->lokasi)
                                    <span>·</span>
                                    <span><i class="bi bi-geo-alt-fill me-1"></i>{{ $featured->lokasi }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Right Column: Vertical List of Small Cards --}}
                <div class="col-lg-5 d-flex flex-column gap-3" data-aos="fade-left">
                    @forelse($completed->skip(1)->take(3) as $k)
                    @php
                        $namaK = strtolower($k->nama);
                        $katK = 'Sosial';
                        if (str_contains($namaK, 'pohon') || str_contains($namaK, 'lingkungan')) {
                            $katK = 'Lingkungan';
                        }
                    @endphp
                    <a href="{{ route('public.kegiatan.show', $k) }}" class="small-completed-card">
                        <span style="font-size: 0.68rem; font-weight: 700; color: #4154F1; letter-spacing: 0.5px; text-transform: uppercase; display: block; margin-bottom: 6px;">
                            {{ $katK }}
                        </span>
                        <h6 style="font-weight:700; color:#0F172A; font-size:0.95rem; margin-bottom:6px; line-height:1.4;">
                            {{ $k->nama }}
                        </h6>
                        <p class="text-muted mb-0" style="font-size:0.8rem; line-height:1.5;">
                            {{ Str::limit(strip_tags($k->deskripsi ?? 'Kegiatan yang telah sukses dilaksanakan.'), 80) }}
                        </p>
                    </a>
                    @empty
                    <div class="h-100 d-flex align-items-center justify-content-center text-muted" style="border:2px dashed #E2E8F0; border-radius:16px; min-height:100px;">
                        Belum ada dokumentasi kegiatan lainnya.
                    </div>
                    @endforelse
                </div>
            </div>
            
            {{-- Pagination --}}
            @if($completed->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $completed->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-5 text-muted" style="border:2px dashed #E2E8F0; border-radius:20px; background:#FFFFFF;">
                <i class="bi bi-calendar-x-fill d-block mb-2" style="font-size:3rem; opacity:0.3; color:#94A3B8;"></i>
                Belum ada data kegiatan selesai.
            </div>
            @endif
        </div>
    </section>
</div>

{{-- Event Detail Modal --}}
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <span id="modalBadge" class="badge px-3 py-2 rounded-pill fw-semibold" style="font-size:0.78rem;"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <h4 id="modalTitle" class="fw-bold text-dark mb-3" style="font-family:'Poppins',sans-serif;"></h4>
                
                <div class="d-flex flex-column gap-2 mb-3 text-muted" style="font-size:0.9rem;">
                    <div><i class="bi bi-calendar3 text-primary me-2"></i><span id="modalDate" class="fw-medium text-dark"></span></div>
                    <div id="modalTimeRow" style="display:none;"><i class="bi bi-clock text-primary me-2"></i><span id="modalTime" class="fw-medium text-dark"></span></div>
                    <div><i class="bi bi-geo-alt text-primary me-2"></i><span id="modalLocation" class="fw-medium text-dark"></span></div>
                </div>

                <div class="p-3 bg-light rounded-3 mb-4" style="font-size:0.88rem; line-height:1.6;" id="modalDescription"></div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold px-4" data-bs-dismiss="modal">Tutup</button>
                    <a id="modalDetailBtn" href="#" class="btn btn-primary rounded-3 fw-semibold px-4" style="background:#4154F1;">
                        Lihat Detail Halaman <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/id.global.min.js"></script>

<script>
    let calendar;
    let rawEvents = [];
    let currentFilter = 'all';

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'Daftar'
            },
            events: function (info, successCallback, failureCallback) {
                fetch(`{{ route('public.kalender.events') }}?start=${info.startStr}&end=${info.endStr}`)
                    .then(res => res.json())
                    .then(data => {
                        rawEvents = data;
                        successCallback(filterData(rawEvents, currentFilter));
                    })
                    .catch(err => {
                        console.error('Gagal memuat event kalender:', err);
                        failureCallback(err);
                    });
            },
            eventClick: function (info) {
                info.jsEvent.preventDefault();

                const props = info.event.extendedProps;
                document.getElementById('modalTitle').innerText = info.event.title.replace(/^[📍🏆]\s*/, '');
                
                const badge = document.getElementById('modalBadge');
                badge.innerText = props.badge;
                if (props.type === 'kegiatan') {
                    badge.className = 'badge bg-primary px-3 py-2 rounded-pill fw-semibold';
                    badge.style.background = '#4154F1';
                } else {
                    badge.className = 'badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold';
                }

                const eventDate = info.event.start ? new Date(info.event.start).toLocaleDateString('id-ID', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                }) : '-';
                document.getElementById('modalDate').innerText = eventDate;
                document.getElementById('modalLocation').innerText = props.lokasi || '-';

                const timeRow = document.getElementById('modalTimeRow');
                if (props.waktu && props.waktu !== '-') {
                    timeRow.style.display = 'block';
                    document.getElementById('modalTime').innerText = props.waktu;
                } else {
                    timeRow.style.display = 'none';
                }

                document.getElementById('modalDescription').innerText = props.deskripsi || 'Tidak ada deskripsi singkat.';
                document.getElementById('modalDetailBtn').href = props.detailUrl;

                const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            }
        });

        calendar.render();

        // Check URL params for initial view (e.g. ?view=card)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('view') === 'card') {
            switchMode('card');
        }
    });

    function switchMode(mode) {
        const calSection = document.getElementById('calendarViewSection');
        const cardSection = document.getElementById('cardViewSection');
        const btnCal = document.getElementById('btnViewCalendar');
        const btnCard = document.getElementById('btnViewCard');

        if (mode === 'calendar') {
            calSection.style.display = 'block';
            cardSection.style.display = 'none';

            btnCal.className = 'mode-tab-btn active';
            btnCard.className = 'mode-tab-btn inactive';

            setTimeout(() => {
                calendar.updateSize();
            }, 100);
        } else {
            calSection.style.display = 'none';
            cardSection.style.display = 'block';

            btnCal.className = 'mode-tab-btn inactive';
            btnCard.className = 'mode-tab-btn active';
        }
    }

    function filterData(events, filter) {
        if (filter === 'all') return events;
        return events.filter(e => e.extendedProps && e.extendedProps.type === filter);
    }

    function filterEvents(type, btnEl) {
        currentFilter = type;
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btnEl.classList.add('active');

        calendar.removeAllEvents();
        calendar.addEventSource(filterData(rawEvents, currentFilter));
    }
</script>
@endpush
