@extends('admin.layouts.app')
@section('title','Polling Anggota')
@section('page-title','Polling & Voting')
@section('breadcrumb','Polling Anggota')
@section('content')

{{-- Polling Aktif --}}
<div class="mb-4">
    <h5 class="fw-bold mb-1">Polling Aktif</h5>
    <small class="text-muted">Berikan suara Anda pada polling yang sedang berlangsung</small>
</div>

@forelse($pollingAktif as $p)
<div class="ui-card mb-3">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-soft badge-soft--warning"><i class="bi bi-clock me-1"></i>Aktif</span>
                    <span class="badge-soft badge-soft--neutral">{{ $p->tipe === 'single' ? '1 Pilihan' : 'Multi Pilihan' }}</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ $p->judul }}</h6>
                @if($p->deskripsi)
                    <p class="text-muted small mb-2">{{ Str::limit($p->deskripsi, 100) }}</p>
                @endif
                <div class="small text-muted">
                    <i class="bi bi-alarm me-1"></i>Berakhir: {{ $p->selesai_at->format('d M Y, H:i') }} WIB
                    &nbsp;·&nbsp; {{ $p->opsi->count() }} pilihan tersedia
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('admin.anggota.polling.show', $p) }}" class="btn-primary-custom">
                    <i class="bi bi-hand-index-thumb me-2"></i>Berikan Suara
                </a>
            </div>
        </div>
    </div>
</div>
@empty
<div class="ui-card mb-4">
    <div class="empty-state">
        <i class="bi bi-bar-chart"></i>
        <h6>Tidak ada polling aktif</h6>
        <p>Belum ada polling yang perlu Anda ikuti saat ini.</p>
    </div>
</div>
@endforelse

{{-- Riwayat Voting --}}
@if($pollingDiikuti->count())
<div class="mt-5 mb-3">
    <h5 class="fw-bold mb-1">Sudah Anda Ikuti</h5>
    <small class="text-muted">Polling yang sudah Anda berikan suara</small>
</div>

@foreach($pollingDiikuti as $p)
<div class="ui-card mb-3">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-soft badge-soft--{{ $p->status_variant }}">{{ $p->status_label }}</span>
                    <span class="badge-soft badge-soft--success"><i class="bi bi-check-circle me-1"></i>Sudah Vote</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ $p->judul }}</h6>
                <div class="small text-muted">
                    <i class="bi bi-people me-1"></i>{{ $p->total_voter }} pemilih
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('admin.anggota.polling.show', $p) }}" class="btn-edit">
                    <i class="bi bi-eye me-1"></i>Lihat Hasil
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

{{-- Polling Selesai --}}
@if($pollingSelesai->count())
<div class="mt-5 mb-3">
    <h5 class="fw-bold mb-1">Polling Selesai</h5>
    <small class="text-muted">Lihat hasil polling yang sudah berakhir</small>
</div>

@foreach($pollingSelesai as $p)
<div class="ui-card mb-3">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <span class="badge-soft badge-soft--success me-2">Selesai</span>
                <span class="fw-semibold text-dark">{{ $p->judul }}</span>
                <div class="small text-muted mt-1">
                    <i class="bi bi-people me-1"></i>{{ $p->total_voter }} pemilih &nbsp;·&nbsp;
                    {{ $p->selesai_at->format('d M Y') }}
                </div>
            </div>
            <a href="{{ route('admin.anggota.polling.show', $p) }}" class="btn btn-sm btn-outline-primary rounded-3">
                <i class="bi bi-bar-chart me-1"></i>Lihat Hasil
            </a>
        </div>
    </div>
</div>
@endforeach
@endif

@endsection
