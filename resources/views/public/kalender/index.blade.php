@extends('public.layouts.app')

@section('title', 'Kalender Kegiatan Terpadu — Karang Taruna')
@section('description', 'Jadwal terpadu seluruh kegiatan, agenda sosial, dan perlombaan Karang Taruna.')

@push('styles')
<style>
    .kalender-hero {
        background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        padding: 120px 0 60px;
        color: white;
        position: relative;
    }
    .fc {
        font-family: 'Inter', sans-serif;
    }
    .fc .fc-toolbar-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.35rem;
        color: #0F172A;
    }
    .fc .fc-button-primary {
        background-color: #4154F1;
        border-color: #4154F1;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 8px 16px;
        text-transform: capitalize;
    }
    .fc .fc-button-primary:hover, .fc .fc-button-primary:focus {
        background-color: #3143D9 !important;
        border-color: #3143D9 !important;
        box-shadow: none !important;
    }
    .fc .fc-button-primary:disabled {
        background-color: #94A3B8;
        border-color: #94A3B8;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #F1F5F9;
    }
    .fc-theme-standard .fc-scrollgrid {
        border-color: #E2E8F0;
        border-radius: 16px;
        overflow: hidden;
    }
    .fc-daygrid-day-number {
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        padding: 8px !important;
    }
    .fc-event {
        cursor: pointer;
        border-radius: 8px !important;
        padding: 3px 6px !important;
        font-size: 0.82rem !important;
        font-weight: 600 !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.06);
        transition: transform 0.15s ease;
    }
    .fc-event:hover {
        transform: scale(1.02);
    }
    .filter-btn {
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 0.88rem;
        font-weight: 600;
        border: 1px solid #E2E8F0;
        background: white;
        color: #475569;
        transition: all 0.2s;
        cursor: pointer;
    }
    .filter-btn.active {
        background: #4154F1;
        color: white;
        border-color: #4154F1;
        box-shadow: 0 4px 12px rgba(65,84,241,0.25);
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="kalender-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <span class="badge mb-2 px-3 py-2 rounded-pill fw-semibold text-white fs-6" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                    📅 Agenda Terpadu
                </span>
                <h1 class="section-title text-white">
                    Kalender Kegiatan & Lomba
                </h1>
                <p class="section-desc text-white-50">
                    Pantau seluruh jadwal kegiatan rutin, aksi sosial, dan kompetisi lomba Karang Taruna secara terpadu dalam tampilan kalender bulanan interaktif.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Main Calendar Container --}}
<section class="section bg-light min-vh-100 py-5">
    <div class="container">
        {{-- Card Controls & Legend --}}
        <div class="ui-card ui-card--lg mb-4" data-aos="fade-up">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">
                    {{-- Filter Buttons --}}
                    <div class="col-md-7 d-flex flex-wrap align-items-center gap-2">
                        <span class="fw-bold text-dark me-2"><i class="bi bi-funnel me-1"></i>Filter Agenda:</span>
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
                    <div class="col-md-5 d-flex justify-content-md-end align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:14px;height:14px;border-radius:4px;background:#4154F1;display:inline-block;"></span>
                            <span class="fw-semibold text-dark small">Kegiatan Taruna</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:14px;height:14px;border-radius:4px;background:#F59E0B;display:inline-block;"></span>
                            <span class="fw-semibold text-dark small">Perlombaan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FullCalendar Render Area --}}
        <div class="ui-card ui-card--lg" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body p-4">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</section>

{{-- Event Detail Modal --}}
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <span id="modalBadge" class="badge-soft badge-soft--info"></span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <h4 id="modalTitle" class="fw-bold text-dark mb-3"></h4>
                
                <div class="d-flex flex-column gap-2 mb-3 text-muted">
                    <div><i class="bi bi-calendar3 text-primary me-2"></i><span id="modalDate" class="fw-medium text-dark"></span></div>
                    <div id="modalTimeRow" style="display:none;"><i class="bi bi-clock text-primary me-2"></i><span id="modalTime" class="fw-medium text-dark"></span></div>
                    <div><i class="bi bi-geo-alt text-primary me-2"></i><span id="modalLocation" class="fw-medium text-dark"></span></div>
                </div>

                <div class="p-3 bg-light rounded-3 mb-4" id="modalDescription"></div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold px-4" data-bs-dismiss="modal">Tutup</button>
                    <a id="modalDetailBtn" href="#" class="btn btn-primary rounded-3 fw-semibold px-4">
                        Lihat Halaman Detail <i class="bi bi-arrow-right ms-1"></i>
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
            initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
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
                    badge.className = 'badge-soft badge-soft--info';
                } else {
                    badge.className = 'badge-soft badge-soft--warning';
                }

                // Date & Location
                const eventDate = info.event.start ? new Date(info.event.start).toLocaleDateString('id-ID', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                }) : '-';
                document.getElementById('modalDate').innerText = eventDate;
                document.getElementById('modalLocation').innerText = props.lokasi || '-';

                // Time Row (for Lomba)
                const timeRow = document.getElementById('modalTimeRow');
                if (props.waktu && props.waktu !== '-') {
                    timeRow.style.display = 'block';
                    document.getElementById('modalTime').innerText = props.waktu;
                } else {
                    timeRow.style.display = 'none';
                }

                // Description
                document.getElementById('modalDescription').innerText = props.deskripsi || 'Tidak ada deskripsi singkat.';

                // Detail Button
                document.getElementById('modalDetailBtn').href = props.detailUrl;

                // Show Modal
                const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            }
        });

        calendar.render();
    });

    function filterData(events, filter) {
        if (filter === 'all') return events;
        return events.filter(e => e.extendedProps && e.extendedProps.type === filter);
    }

    function filterEvents(type, btnEl) {
        currentFilter = type;
        
        // Active Button styling
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btnEl.classList.add('active');

        // Refetch / Filter events in FullCalendar
        calendar.removeAllEvents();
        calendar.addEventSource(filterData(rawEvents, currentFilter));
    }
</script>
@endpush
