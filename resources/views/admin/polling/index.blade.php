@extends('admin.layouts.app')
@section('title','Polling')
@section('page-title','Polling & Voting')
@section('breadcrumb','Admin / Polling')
@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold">Daftar Polling</h5>
        <small class="text-muted">{{ $daftarPolling->total() }} polling terdaftar</small>
    </div>
    <a href="{{ route('admin.polling.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i>Buat Polling Baru
    </a>
</div>

{{-- Filter Status --}}
<form method="GET" class="mb-4 d-flex gap-2 flex-wrap">
    @foreach([''=>'Semua','draft'=>'Draft','aktif'=>'Aktif','selesai'=>'Selesai'] as $val => $label)
        <button type="submit" name="status" value="{{ $val }}"
            class="filter-chip {{ request('status', '') === $val ? 'filter-chip--active' : '' }}">
            {{ $label }}
        </button>
    @endforeach
</form>

@push('styles')
<style>
    .filter-chip {
        padding: 6px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;
        border: 1px solid #E2E8F0; background: white; color: #475569; cursor: pointer; transition: all 0.2s;
    }
    .filter-chip--active { background: #4154F1; color: white; border-color: #4154F1; }
    .filter-chip:hover:not(.filter-chip--active) { background: #F1F5F9; }
</style>
@endpush

{{-- Grid Polling --}}
@forelse($daftarPolling as $p)
<div class="ui-card mb-3">
    <div class="card-body p-4">
        <div class="row align-items-start g-3">
            <div class="col-md-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-soft badge-soft--{{ $p->status_variant }}">
                        <i class="bi bi-circle-fill" style="font-size:0.45rem;"></i>
                        {{ $p->status_label }}
                    </span>
                    <span class="badge-soft badge-soft--neutral">
                        {{ $p->tipe === 'single' ? '1 Pilihan' : 'Multi Pilihan' }}
                    </span>
                    @if($p->tampil_publik)
                        <span class="badge-soft badge-soft--info"><i class="bi bi-globe me-1"></i>Publik</span>
                    @endif
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ $p->judul }}</h6>
                @if($p->deskripsi)
                    <p class="text-muted small mb-2">{{ Str::limit($p->deskripsi, 120) }}</p>
                @endif
                <div class="small text-muted d-flex flex-wrap gap-3">
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $p->mulai_at->format('d M Y') }} — {{ $p->selesai_at->format('d M Y') }}</span>
                    <span><i class="bi bi-list-ul me-1"></i>{{ $p->opsi_count }} opsi</span>
                    <span><i class="bi bi-people me-1"></i>{{ $p->votes_count }} vote</span>
                </div>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-md-end align-items-start flex-wrap">
                <a href="{{ route('admin.polling.hasil', $p) }}" class="btn-primary-custom" style="font-size:0.82rem;padding:7px 14px;">
                    <i class="bi bi-bar-chart-fill me-1"></i>Hasil
                </a>
                <a href="{{ route('admin.polling.edit', $p) }}" class="btn-edit">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form action="{{ route('admin.polling.destroy', $p) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus polling ini? Semua vote ikut terhapus.')">
                    @csrf @method('DELETE')
                    <button class="btn-delete"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@empty
<div class="empty-state">
    <i class="bi bi-bar-chart"></i>
    <h6>Belum ada polling</h6>
    <p>Buat polling baru untuk mengumpulkan suara anggota.</p>
</div>
@endforelse

@if($daftarPolling->hasPages())
<div class="mt-4">{{ $daftarPolling->links() }}</div>
@endif
@endsection
