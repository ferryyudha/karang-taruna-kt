@extends('admin.layouts.app')
@section('title', 'Hasil Polling — ' . $polling->judul)
@section('page-title', 'Hasil Polling')
@section('breadcrumb', 'Admin / Polling / Hasil')
@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Hasil & Partisipasi Polling</h4>
        <p class="text-muted small mb-0">Lihat rekapitulasi pilihan suara dan daftar anggota yang belum memilih.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.polling.index') }}" class="btn btn-light rounded-3 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('admin.polling.edit', $polling) }}" class="btn-primary-custom" style="padding: 9px 20px;">
            <i class="bi bi-pencil me-1"></i>Edit Polling
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT COLUMN: REKAPITULASI & GRAFIK --}}
    <div class="col-lg-8">
        {{-- Polling Info & Stats --}}
        <div class="ui-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge-soft badge-soft--{{ $polling->status_variant }}">{{ $polling->status_label }}</span>
                    <span class="badge-soft badge-soft--neutral">{{ $polling->tipe === 'single' ? '1 Pilihan' : 'Multi Pilihan' }}</span>
                    @if($polling->tampil_publik)
                        <span class="badge-soft badge-soft--info"><i class="bi bi-globe me-1"></i>Tampil di Publik</span>
                    @endif
                </div>
                <h4 class="fw-bold text-dark mb-2">{{ $polling->judul }}</h4>
                @if($polling->deskripsi)
                    <p class="text-muted small mb-3">{{ $polling->deskripsi }}</p>
                @endif
                
                {{-- Quick Stats Row --}}
                <div class="row g-3 mt-1">
                    <div class="col-sm-6">
                        <div class="stat-card stat-card--info d-flex align-items-center gap-3">
                            <div class="stat-icon"><i class="bi bi-people"></i></div>
                            <div>
                                <div class="stat-card__number">{{ $totalVoter }}</div>
                                <div class="stat-card__label">Partisipan (Sudah Vote)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="stat-card stat-card--success d-flex align-items-center gap-3">
                            <div class="stat-icon"><i class="bi bi-check-all"></i></div>
                            <div>
                                <div class="stat-card__number">{{ $totalVotes }}</div>
                                <div class="stat-card__label">Total Suara Masuk</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suara Per Opsi --}}
        <div class="ui-card mb-4">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark">Rekapitulasi Opsi Pilihan</h5>
            </div>
            <div class="card-body p-4">
                @if($totalVotes === 0)
                    <div class="empty-state">
                        <i class="bi bi-inbox-fill text-muted"></i>
                        <h6>Belum ada suara masuk</h6>
                        <p class="small">Sebarkan tautan polling ke anggota untuk mengumpulkan suara.</p>
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
                                <span class="fw-semibold text-dark" style="font-size:0.9rem;">{{ $opsi->teks_opsi }}</span>
                                <span class="small fw-bold text-primary">{{ $jumlah }} suara ({{ $persen }}%)</span>
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

                    {{-- Chart.js --}}
                    <div class="mt-5 pt-3 border-top">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-line me-2"></i>Grafik Batang Perbandingan</h6>
                        <div style="position: relative; height:280px; width:100%;">
                            <canvas id="chartHasil"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: INFO & BELUM VOTING --}}
    <div class="col-lg-4">
        {{-- Periode Polling --}}
        <div class="ui-card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Polling</h6>
                <div class="stack-sm small">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Mulai</span>
                        <strong class="text-dark">{{ $polling->mulai_at->format('d M Y, H:i') }} WIB</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Selesai</span>
                        <strong class="text-dark">{{ $polling->selesai_at->format('d M Y, H:i') }} WIB</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Tipe Pilihan</span>
                        <strong class="text-dark">{{ $polling->tipe === 'single' ? 'Pilih 1 Opsi' : 'Boleh Pilih Lebih' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Pembuat</span>
                        <strong class="text-dark">{{ $polling->pembuatBy?->name ?? '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Yang Belum Voting --}}
        <div class="ui-card mb-4">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark">Belum Voting</h6>
                <span class="badge-soft badge-soft--danger fw-bold">{{ $belumVote->count() }} orang</span>
            </div>
            <div class="card-body p-3">
                @if($belumVote->count() === 0)
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                        <span class="small fw-semibold text-dark d-block">Semua Anggota Sudah Vote</span>
                        <span style="font-size:0.75rem;">Partisipasi 100% tercapai!</span>
                    </div>
                @else
                    <div class="stack-sm" style="max-height: 400px; overflow-y: auto; padding-right: 4px;" id="belumVoteList">
                        @foreach($belumVote as $user)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3 hover-voter">
                            <div class="d-flex align-items-center gap-2">
                                {{-- Avatar Initial --}}
                                <div class="avatar-initial">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 140px; font-size: 0.85rem;" title="{{ $user->name }}">
                                        {{ $user->name }}
                                    </div>
                                    <div style="font-size:0.72rem; color:#94A3B8;">{{ $user->role->name ?? 'Anggota' }}</div>
                                </div>
                            </div>
                            
                            {{-- WhatsApp / Contact Action --}}
                            @if($user->phone)
                                @php
                                    $phoneClean = preg_replace('/[^0-9]/', '', $user->phone);
                                    // Ganti 08xxx menjadi 628xxx untuk format API Whatsapp
                                    if (str_starts_with($phoneClean, '0')) {
                                        $phoneClean = '62' . substr($phoneClean, 1);
                                    }
                                    $waMessage = rawurlencode("Halo {$user->name}, mengingatkan untuk berpartisipasi dalam Polling \"{$polling->judul}\" di website Karang Taruna. Terima kasih!");
                                @endphp
                                <a href="https://wa.me/{{ $phoneClean }}?text={{ $waMessage }}" target="_blank"
                                    class="btn-whatsapp" title="Kirim Pengingat WA">
                                    <i class="bi bi-whatsapp"></i> <span class="wa-text">Ingatkan</span>
                                </a>
                            @else
                                <span class="text-muted" style="font-size:0.7rem;" title="Nomor telepon tidak tersedia">no phone</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-voter {
        transition: background 0.2s ease;
    }
    .hover-voter:hover {
        background: #F8FAFC;
    }
    .avatar-initial {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4154F1, #7C3AED);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
    }
    .btn-whatsapp {
        background: rgba(37, 211, 102, 0.1);
        color: #25D366;
        border: 1px solid rgba(37, 211, 102, 0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.25s;
    }
    .btn-whatsapp:hover {
        background: #25D366;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 211, 102, 0.2);
    }
    /* Custom Scrollbar for voter list */
    #belumVoteList::-webkit-scrollbar {
        width: 4px;
    }
    #belumVoteList::-webkit-scrollbar-thumb {
        background: #E2E8F0;
        border-radius: 4px;
    }
    @media (max-width: 575px) {
        .wa-text { display: none; }
        .btn-whatsapp { padding: 4px 8px; }
    }
</style>
@endpush

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
                backgroundColor: 'rgba(65, 84, 241, 0.85)',
                hoverBackgroundColor: '#4154F1',
                borderColor: '#4154F1',
                borderWidth: 1,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.raw} suara`
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1, color: '#94A3B8' }, 
                    grid: { color: '#F1F5F9' } 
                },
                x: { 
                    ticks: { color: '#475569', font: { weight: '500' } },
                    grid: { display: false } 
                }
            }
        }
    });
</script>
@endpush
@endif
@endsection
