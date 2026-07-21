@extends('admin.layouts.app')
@section('title','Lomba')
@section('page-title','Lomba')
@section('breadcrumb','Admin / Lomba')
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="mb-0 fw-bold">Daftar Lomba</h5>
        <small class="text-muted">{{ $lomba->total() }} lomba terdaftar</small>
    </div>
    <a href="{{ route('admin.lomba.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i>Tambah Lomba
    </a>
</div>

<div class="ui-card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Kegiatan</label>
                <select name="kegiatan_id" class="form-select">
                    <option value="">Semua Kegiatan</option>
                    @foreach($kegiatanList as $k)
                        <option value="{{ $k->id }}" {{ request('kegiatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="persiapan" {{ request('status')=='persiapan'?'selected':'' }}>Persiapan</option>
                    <option value="berlangsung" {{ request('status')=='berlangsung'?'selected':'' }}>Berlangsung</option>
                    <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('admin.lomba.index') }}" class="btn btn-light rounded-3">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
@forelse($lomba as $l)
<div class="col-md-6 col-lg-4">
    <div class="ui-card p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="fw-bold mb-0 fs-6">{{ $l->nama }}</h6>
            <span class="badge-soft badge-soft--{{ match($l->status){ 'berlangsung'=>'info','selesai'=>'success',default=>'warning' } }}">{{ $l->status_label }}</span>
        </div>
        <div class="text-muted small mb-1">
            <i class="bi bi-collection me-1"></i>{{ $l->kegiatan->nama }}
        </div>
        <div class="text-muted small">
            <i class="bi bi-calendar3 me-1"></i>{{ $l->tanggal->format('d M Y') }}
            @if($l->waktu_mulai) &nbsp;·&nbsp; <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($l->waktu_mulai)->format('H:i') }} @endif
        </div>
        @if($l->lokasi)
        <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $l->lokasi }}</div>
        @endif
        <div class="d-flex gap-3 mt-2 small text-muted">
            <span><i class="bi bi-people me-1"></i>{{ $l->peserta_count ?? $l->peserta()->count() }} peserta</span>
            <span><i class="bi bi-box-seam me-1"></i>{{ $l->peralatan_count ?? $l->peralatan()->count() }} alat</span>
        </div>
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('admin.lomba.show', $l) }}" class="btn-primary-custom flex-fill text-center small py-1">
                <i class="bi bi-eye me-1"></i>Kelola
            </a>
            <a href="{{ route('admin.lomba.edit', $l) }}" class="btn-edit">
                <i class="bi bi-pencil"></i>
            </a>
            <form method="POST" action="{{ route('admin.lomba.destroy', $l) }}" onsubmit="return confirm('Hapus lomba ini? Semua data peralatan & peserta ikut terhapus.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    </div>
</div>
@empty
<div class="col-12">
    <div class="empty-state">
        <i class="bi bi-trophy"></i>
        <h6>Belum ada lomba</h6>
        <p>Belum ada lomba terdaftar. <a href="{{ route('admin.lomba.create') }}" class="text-primary">Tambah sekarang</a></p>
    </div>
</div>
@endforelse
</div>
@if($lomba->hasPages())
<div class="mt-4">{{ $lomba->appends(request()->query())->links() }}</div>
@endif
@endsection
