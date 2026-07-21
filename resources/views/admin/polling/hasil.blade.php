@extends('admin.layouts.app')
@section('title','Hasil Polling — ' . $polling->judul)
@section('page-title','Hasil Polling')
@section('breadcrumb','Admin / Polling / Hasil')
@section('content')

{{-- Header Card --}}
<div class="ui-card mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-soft badge-soft--{{ $polling->status_variant }}">{{ $polling->status_label }}</span>
                    <span class="badge-soft badge-soft--neutral">{{ $polling->tipe === 'single' ? '1 Pilihan' : 'Multi Pilihan' }}</span>
                    @if($polling->tampil_publik)<span class="badge-soft badge-soft--info"><i class="bi bi-globe me-1"></i>Publik</span>@endif
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $polling->judul }}</h4>
                @if($polling->deskripsi)
                    <p class="text-muted mb-2">{{ $polling->deskripsi }}</p>
                @endif
                <div class="small text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $polling->mulai_at->format('d M Y H:i') }} — {{ $polling->selesai_at->format('d M Y H:i') }}
                    &nbsp;·&nbsp; Dibuat oleh <strong>{{ $polling->pembuatBy?->name ?? '-' }}</strong>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="stat-card stat-card--info p-3">
                            <div class="stat-card__number">{{ $totalVoter }}</div>
                            <div class="stat-card__label">Total Pemilih</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card stat-card--success p-3">
                            <div class="stat-card__number">{{ $totalVotes }}</div>
                            <div class="stat-card__label">Total Suara</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hasil Per Opsi --}}
<div class="ui-card mb-4">
    <div class="card-header bg-white p-4 border-bottom">
        <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Rekapitulasi Suara</h5>
    </div>
    <div class="card-body p-4">
        @if($totalVotes === 0)
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h6>Belum ada yang memilih</h6>
                <p>Belum ada suara masuk pada polling ini. Bagikan polling kepada anggota!</p>
            </div>
        @else
            <div class="stack-md">
                @foreach($polling->opsi->sortByDesc(fn($o) => $o->jumlah_vote) as $opsi)
                @php
                    $persen = $opsi->persentase;
                    $jumlah = $opsi->jumlah_vote;
                @endphp
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-semibold text-dark">{{ $opsi->teks_opsi }}</span>
                        <span class="small fw-bold text-primary">{{ $jumlah }} suara &nbsp;({{ $persen }}%)</span>
                    </div>
                    <div class="progress rounded-pill" style="height:12px; background:#F1F5F9;">
                        <div class="progress-bar rounded-pill"
                            role="progressbar"
                            style="width:{{ $persen }}%; background:var(--primary, #4154F1); transition:width 0.6s ease;"
                            aria-valuenow="{{ $persen }}"
                            aria-valuemin="0"
                            aria-valuemax="100">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Chart.js Grafik Batang --}}
            <div class="mt-5">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart me-2"></i>Grafik Perbandingan</h6>
                <canvas id="chartHasil" style="max-height:280px;"></canvas>
            </div>
        @endif
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('admin.polling.edit', $polling) }}" class="btn-edit"><i class="bi bi-pencil me-1"></i>Edit Polling</a>
    <a href="{{ route('admin.polling.index') }}" class="btn btn-light rounded-3 fw-semibold">← Kembali</a>
</div>

@if($totalVotes > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('chartHasil').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($polling->opsi->sortByDesc(fn($o) => $o->jumlah_vote)->pluck('teks_opsi')) !!},
            datasets: [{
                label: 'Jumlah Suara',
                data: {!! json_encode($polling->opsi->sortByDesc(fn($o) => $o->jumlah_vote)->map(fn($o) => $o->jumlah_vote)->values()) !!},
                backgroundColor: 'rgba(65, 84, 241, 0.8)',
                borderColor: '#4154F1',
                borderWidth: 1,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.raw} suara`
                    }
                }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#F1F5F9' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
@endif
@endsection
