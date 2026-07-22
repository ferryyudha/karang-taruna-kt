@extends('public.layouts.app')
@section('title', 'Hasil Polling Warga — Karang Taruna')
@section('description', 'Transparansi hasil polling dan voting anggota Karang Taruna yang dibuka untuk publik.')

@section('content')
<section class="bg-primary text-white py-5 position-relative overflow-hidden" style="margin-top:60px;background:linear-gradient(135deg,#1E3A8A,#4154F1,#7C3AED)!important;">
    <div class="container py-4">
        <span class="badge mb-3 px-3 py-2 rounded-pill fw-semibold text-white d-inline-block" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
            <i class="bi bi-bar-chart-fill me-1"></i>Hasil Polling Publik
        </span>
        <h1 class="section-title text-white">Polling & Voting Anggota</h1>
        <p class="section-desc text-white-50">Hasil voting anggota Karang Taruna yang dibuka untuk transparansi publik.</p>
    </div>
</section>

<div class="container py-5">
    @forelse($daftarPolling as $polling)
    @php $totalVotes = $polling->votes->count(); @endphp
    <div class="ui-card ui-card--lg mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex align-items-start gap-3 flex-wrap mb-3">
                <div class="flex-grow-1">
                    <span class="badge-soft badge-soft--success mb-2 d-inline-block">Selesai</span>
                    <h4 class="fw-bold text-dark mb-1">{{ $polling->judul }}</h4>
                    @if($polling->deskripsi)
                        <p class="text-muted mb-2">{{ $polling->deskripsi }}</p>
                    @endif
                    <div class="small text-muted">
                        <i class="bi bi-calendar3 me-1"></i>{{ $polling->mulai_at->format('d M Y') }} — {{ $polling->selesai_at->format('d M Y') }}
                        &nbsp;·&nbsp; <i class="bi bi-people me-1"></i>{{ $polling->total_voter }} pemilih
                    </div>
                </div>
                <div class="text-center">
                    <div class="stat-card__number">{{ $polling->total_voter }}</div>
                    <div class="stat-card__label">Pemilih</div>
                </div>
            </div>

            @if($totalVotes === 0)
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h6>Belum ada suara</h6>
                    <p>Tidak ada suara yang masuk pada polling ini.</p>
                </div>
            @else
                <div class="stack-md">
                    @foreach($polling->opsi->sortByDesc(fn($o) => $o->jumlah_vote) as $opsi)
                    @php $persen = $opsi->persentase; @endphp
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold text-dark">{{ $opsi->teks_opsi }}</span>
                            <span class="small fw-bold text-primary">{{ $opsi->jumlah_vote }} suara ({{ $persen }}%)</span>
                        </div>
                        <div class="progress rounded-pill" style="height:10px;background:#F1F5F9;">
                            <div class="progress-bar rounded-pill"
                                style="width:{{ $persen }}%;background:#4154F1;transition:width 0.6s ease;"
                                role="progressbar">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state py-5">
        <i class="bi bi-bar-chart" style="font-size:3rem;opacity:0.3;"></i>
        <h5 class="mt-3 text-muted">Belum ada hasil polling publik</h5>
        <p class="text-muted">Hasil polling akan ditampilkan di sini setelah polling selesai dan dipublikasikan.</p>
    </div>
    @endforelse

    @if($daftarPolling->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $daftarPolling->links() }}</div>
    @endif
</div>
@endsection
