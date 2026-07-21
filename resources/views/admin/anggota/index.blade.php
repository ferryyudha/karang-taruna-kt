@extends('admin.layouts.app')
@section('title','Anggota')
@section('page-title','Anggota Organisasi')
@section('breadcrumb','Admin / Anggota')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Daftar Anggota</h5>
        <small class="text-muted">{{ $anggota->total() }} anggota terdaftar</small>
    </div>
    <a href="{{ route('admin.anggota.create') }}" class="btn-primary-custom">
        <i class="bi bi-person-plus me-1"></i>Tambah Anggota
    </a>
</div>
<div class="row g-3">
@forelse($anggota as $a)
<div class="col-md-6 col-lg-3">
    <div class="ui-card text-center p-4">
        <div class="rounded-circle mx-auto mb-3 overflow-hidden d-flex align-items-center justify-content-center" style="width:80px;height:80px;background:linear-gradient(135deg,#4154F1,#7C3AED);">
            @if($a->foto)
                <img src="{{ Storage::url($a->foto) }}" alt="{{ $a->nama }}" class="w-100 h-100 object-fit-cover">
            @else
                <span class="fs-3 fw-bold text-white">{{ strtoupper(substr($a->nama,0,1)) }}</span>
            @endif
        </div>
        <h6 class="fw-bold mb-1 fs-6">{{ $a->nama }}</h6>
        <div class="mb-2">
            <span class="badge-soft badge-soft--info small">{{ $a->jabatan }}</span>
        </div>
        @if($a->periode)
        <div class="text-muted small"><i class="bi bi-calendar2 me-1"></i>{{ $a->periode }}</div>
        @endif
        <div class="d-flex gap-1 justify-content-center mt-3">
            <span class="badge-soft {{ $a->aktif ? 'badge-soft--success' : 'badge-soft--neutral' }}">{{ $a->aktif ? 'Aktif' : 'Nonaktif' }}</span>
        </div>
        <div class="d-flex gap-2 mt-2 justify-content-center">
            <a href="{{ route('admin.anggota.edit', $a) }}" class="btn-edit"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="{{ route('admin.anggota.destroy', $a) }}" onsubmit="return confirm('Hapus anggota ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    </div>
</div>
@empty
<div class="col-12">
    <div class="empty-state">
        <i class="bi bi-people"></i>
        <h6>Belum ada anggota</h6>
        <p>Belum ada anggota terdaftar. <a href="{{ route('admin.anggota.create') }}" class="text-primary">Tambah sekarang</a></p>
    </div>
</div>
@endforelse
</div>
@if($anggota->hasPages())
<div class="mt-4">{{ $anggota->links() }}</div>
@endif
@endsection
