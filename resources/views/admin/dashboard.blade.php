@extends('admin.layouts.app')
@section('title', 'Dashboard Analytics')
@section('page-title', 'Dashboard Analytics')
@section('breadcrumb', 'Ringkasan statistik kinerja & tren keuangan tahun ' . $selectedYear)

@section('content')
{{-- Filter Tahun & Header --}}
<div class="ui-card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="row align-items-center g-3">
            <div class="col-md-6 d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart-line-fill text-primary fs-4"></i>
                <div>
                    <h6 class="mb-0 fw-bold">Ringkasan Statistik & Tren</h6>
                    <small class="text-muted">Statistik & tren keuangan tahun {{ $selectedYear }}</small>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end align-items-center gap-2">
                <label class="form-label mb-0 me-1 fw-bold text-nowrap">Pilih Tahun:</label>
                <select name="tahun" class="form-select w-auto" onchange="this.form.submit()">
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

{{-- Stat Cards Ringkasan Keuangan --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3" data-aos="fade-up">
        <div class="stat-card stat-card--info">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="badge-soft badge-soft--info">Total Saldo</span>
            </div>
            <div class="stat-card__number">Rp {{ number_format($totalSaldoKas, 0, ',', '.') }}</div>
            <div class="stat-card__label">Saldo Kas Keseluruhan</div>
        </div>
    </div>
    
    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-card stat-card--success">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon">
                    <i class="bi bi-arrow-down-left-circle-fill"></i>
                </div>
                <span class="badge-soft badge-soft--success">{{ $selectedYear }}</span>
            </div>
            <div class="stat-card__number text-success">Rp {{ number_format($totalPemasukanTahun, 0, ',', '.') }}</div>
            <div class="stat-card__label">Total Pemasukan Tahunan</div>
        </div>
    </div>

    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card stat-card--danger">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon">
                    <i class="bi bi-arrow-up-right-circle-fill"></i>
                </div>
                <span class="badge-soft badge-soft--danger">{{ $selectedYear }}</span>
            </div>
            <div class="stat-card__number text-danger">Rp {{ number_format($totalPengeluaranTahun, 0, ',', '.') }}</div>
            <div class="stat-card__label">Total Pengeluaran Tahunan</div>
        </div>
    </div>

    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card stat-card--warning">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="stat-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <span class="badge-soft badge-soft--warning">Surplus/Defisit</span>
            </div>
            <div class="stat-card__number {{ $netFlowTahun >= 0 ? 'text-dark' : 'text-danger' }}">
                Rp {{ number_format($netFlowTahun, 0, ',', '.') }}
            </div>
            <div class="stat-card__label">Net Flow Kas {{ $selectedYear }}</div>
        </div>
    </div>
</div>

{{-- Stat Cards Operasional --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card--info d-flex align-items-center gap-3">
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-card__number">{{ $stats['anggota'] }}</div>
                <div class="stat-card__label">Anggota Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card--success d-flex align-items-center gap-3">
            <div class="stat-icon">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
            <div>
                <div class="stat-card__number">{{ $stats['kegiatan'] }}</div>
                <div class="stat-card__label">Kegiatan ({{ $selectedYear }})</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card--warning d-flex align-items-center gap-3">
            <div class="stat-icon">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <div>
                <div class="stat-card__number">{{ $stats['lomba'] }}</div>
                <div class="stat-card__label">Lomba ({{ $selectedYear }})</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-card--danger d-flex align-items-center gap-3">
            <div class="stat-icon">
                <i class="bi bi-chat-square-exclamation-fill"></i>
            </div>
            <div>
                <div class="stat-card__number">
                    {{ $stats['pengaduan'] }}
                    <span class="badge-soft badge-soft--success ms-1">{{ $pengaduanSelesaiRate }}% Selesai</span>
                </div>
                <div class="stat-card__label">Laporan Pengaduan</div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-4 mb-4">
    {{-- Chart Keuangan Bulanan --}}
    <div class="col-lg-8" data-aos="fade-up">
        <div class="ui-card h-100">
            <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="mb-0 fw-bold"><i class="bi bi-graph-up text-primary me-2"></i>Tren Keuangan Bulanan ({{ $selectedYear }})</h6>
                    <small class="text-muted">Perbandingan Pemasukan (Hijau) vs Pengeluaran (Merah) per bulan</small>
                </div>
            </div>
            <div class="card-body p-3">
                <canvas id="keuanganChart" height="110"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart Status Pengaduan Warga --}}
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="ui-card h-100">
            <div class="card-header bg-white border-bottom p-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-pie-chart-fill text-warning me-2"></i>Pengaduan Warga ({{ $selectedYear }})</h6>
                <small class="text-muted">Status penanganan laporan publik</small>
            </div>
            <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                @if($pengaduanTotal > 0)
                    <div class="w-100" style="max-width:210px;">
                        <canvas id="pengaduanChart"></canvas>
                    </div>
                    <div class="mt-3 w-100 stack-sm">
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span><i class="bi bi-circle-fill text-info me-1"></i> Diterima:</span>
                            <span class="fw-bold">{{ $pengaduanStatus['diterima'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span><i class="bi bi-circle-fill text-warning me-1"></i> Diproses:</span>
                            <span class="fw-bold">{{ $pengaduanStatus['diproses'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span><i class="bi bi-circle-fill text-success me-1"></i> Selesai:</span>
                            <span class="fw-bold">{{ $pengaduanStatus['selesai'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span><i class="bi bi-circle-fill text-danger me-1"></i> Ditolak:</span>
                            <span class="fw-bold">{{ $pengaduanStatus['ditolak'] }}</span>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-chat-square-x"></i>
                        <h6>Belum ada pengaduan</h6>
                        <p>Belum ada laporan pengaduan di tahun {{ $selectedYear }}.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Recent Activities & Tables --}}
<div class="row g-4">
    {{-- Upcoming Kegiatan --}}
    <div class="col-lg-4" data-aos="fade-up">
        <div class="ui-card h-100">
            <div class="card-header bg-white border-bottom p-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check text-primary me-2"></i>Kegiatan Mendatang</h6>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingKegiatan as $k)
                <div class="d-flex align-items-start gap-3 p-3 border-bottom">
                    <div class="stat-icon bg-light text-primary flex-shrink-0">
                        <i class="bi bi-calendar2"></i>
                    </div>
                    <div>
                        <div class="fw-semibold text-dark">{{ Str::limit($k->nama, 30) }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>{{ $k->tanggal->format('d M Y') }}
                        </div>
                        @if($k->lokasi)
                        <div class="text-muted small">
                            <i class="bi bi-geo-alt me-1"></i>{{ $k->lokasi }}
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h6>Tidak ada kegiatan</h6>
                    <p>Tidak ada agenda kegiatan mendatang saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Pengumuman --}}
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="50">
        <div class="ui-card h-100">
            <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-megaphone text-warning me-2"></i>Pengumuman Terbaru</h6>
                @can('pengumuman', auth()->user())
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
                @endcan
            </div>
            <div class="card-body p-0">
                @if($recentPengumuman->count() > 0)
                <table class="ui-table">
                    <thead><tr><th>Judul</th><th>Tanggal</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($recentPengumuman as $p)
                    <tr>
                        <td>{{ Str::limit($p->judul, 25) }}</td>
                        <td>{{ $p->tanggal->format('d M Y') }}</td>
                        <td>
                            <span class="badge-soft badge-soft--{{ $p->status === 'publish' ? 'success' : 'neutral' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h6>Belum ada pengumuman</h6>
                    <p>Pengumuman terbaru akan muncul di sini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Kegiatan --}}
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="ui-card h-100">
            <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-event text-success me-2"></i>Kegiatan Terbaru</h6>
                @if(auth()->user()->canAccess('kegiatan'))
                <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
                @endif
            </div>
            <div class="card-body p-0">
                @if($recentKegiatan->count() > 0)
                <table class="ui-table">
                    <thead><tr><th>Nama Kegiatan</th><th>Tanggal</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($recentKegiatan as $k)
                    <tr>
                        <td>{{ Str::limit($k->nama, 22) }}</td>
                        <td>{{ $k->tanggal->format('d M Y') }}</td>
                        <td>
                            @php
                                $variant = match($k->status) {
                                    'upcoming' => 'info',
                                    'ongoing' => 'warning',
                                    'completed' => 'success',
                                    default => 'neutral'
                                };
                            @endphp
                            <span class="badge-soft badge-soft--{{ $variant }}">{{ $k->status_label }}</span>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h6>Belum ada kegiatan</h6>
                    <p>Daftar kegiatan terbaru akan muncul di sini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 500, once: true });

// 1. Chart Keuangan Bulanan (Pemasukan vs Pengeluaran)
const ctxKeuangan = document.getElementById('keuanganChart').getContext('2d');
new Chart(ctxKeuangan, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Pemasukan (Rp)',
                data: @json($chartPemasukan),
                backgroundColor: 'rgba(22, 163, 74, 0.85)',
                borderColor: '#16A34A',
                borderWidth: 1.5,
                borderRadius: 6,
            },
            {
                label: 'Pengeluaran (Rp)',
                data: @json($chartPengeluaran),
                backgroundColor: 'rgba(220, 38, 38, 0.85)',
                borderColor: '#DC2626',
                borderWidth: 1.5,
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top', labels: { font: { family: 'Inter', weight: '600' } } },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let val = context.raw || 0;
                        return context.dataset.label + ': Rp ' + val.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: {
                    callback: function(val) {
                        if (val >= 1000000) return 'Rp ' + (val / 1000000) + ' Jt';
                        if (val >= 1000) return 'Rp ' + (val / 1000) + ' Rb';
                        return 'Rp ' + val;
                    }
                }
            },
            x: { grid: { display: false } }
        }
    }
});

// 2. Chart Pengaduan Status (Doughnut)
@if($pengaduanTotal > 0)
const ctxPengaduan = document.getElementById('pengaduanChart').getContext('2d');
new Chart(ctxPengaduan, {
    type: 'doughnut',
    data: {
        labels: ['Diterima', 'Diproses', 'Selesai', 'Ditolak'],
        datasets: [{
            data: [
                {{ $pengaduanStatus['diterima'] }},
                {{ $pengaduanStatus['diproses'] }},
                {{ $pengaduanStatus['selesai'] }},
                {{ $pengaduanStatus['ditolak'] }}
            ],
            backgroundColor: ['#06B6D4', '#F59E0B', '#10B981', '#EF4444'],
            borderWidth: 2,
            borderColor: '#FFFFFF'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        cutout: '68%'
    }
});
@endif
</script>
@endpush
