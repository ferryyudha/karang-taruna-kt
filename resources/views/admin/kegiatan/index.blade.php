@extends('admin.layouts.app')
@section('title','Kegiatan')
@section('page-title','Kegiatan')
@section('breadcrumb','Admin / Kegiatan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Daftar Kegiatan</h5>
        <small class="text-muted">{{ $kegiatan->total() }} kegiatan terdaftar</small>
    </div>
    <a href="{{ route('admin.kegiatan.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kegiatan
    </a>
</div>
<div class="row g-3">
@forelse($kegiatan as $k)
<div class="col-md-6 col-lg-4">
    <div class="ui-card overflow-hidden">
        <div class="position-relative" style="height:150px;background:linear-gradient(135deg,#1B2537,#1E3A8A);">
            @if($k->foto_cover)
                <img src="{{ Storage::url($k->foto_cover) }}" alt="{{ $k->nama }}"
                    class="w-100 h-100 object-fit-cover">
            @else
                <div class="d-flex align-items-center justify-content-center h-100">
                    <i class="bi bi-calendar-event fs-1 text-white opacity-25"></i>
                </div>
            @endif
            <span class="badge-{{ $k->status }} badge-status position-absolute top-0 end-0 m-2">{{ $k->status_label }}</span>
        </div>
        <div class="p-3">
            <h6 class="fw-bold mb-1 fs-6">{{ $k->nama }}</h6>
            <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>{{ $k->tanggal->format('d M Y') }}
                @if($k->lokasi) &nbsp;·&nbsp; <i class="bi bi-geo-alt me-1"></i>{{ $k->lokasi }} @endif
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('admin.kegiatan.edit', $k) }}" class="btn-edit flex-fill text-center">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('admin.dokumentasi.index', ['kegiatan_id' => $k->id]) }}" class="btn btn-light border flex-fill text-center rounded-3 small py-1">
                    <i class="bi bi-images me-1"></i>Foto
                </a>
                <form method="POST" action="{{ route('admin.kegiatan.destroy', $k) }}" onsubmit="return confirm('Hapus kegiatan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12">
    <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <h6>Belum ada kegiatan</h6>
        <p>Belum ada kegiatan terdaftar. <a href="{{ route('admin.kegiatan.create') }}" class="text-primary">Tambah sekarang</a></p>
    </div>
</div>
@endforelse
</div>
@if($kegiatan->hasPages())
<div class="mt-4">{{ $kegiatan->links() }}</div>
@endif
@endsection
