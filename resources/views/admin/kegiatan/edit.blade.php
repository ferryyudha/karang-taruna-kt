@extends('admin.layouts.app')
@section('title','Edit Kegiatan')
@section('page-title','Edit Kegiatan')
@section('breadcrumb','Admin / Kegiatan / Edit')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-warning"></i>Edit Kegiatan</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kegiatan.update', $kegiatan) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label-admin">Nama Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control form-control-admin"
                    value="{{ old('nama', $kegiatan->nama) }}" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label-admin">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control form-control-admin"
                        value="{{ old('tanggal', $kegiatan->tanggal->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control form-control-admin"
                        value="{{ old('lokasi', $kegiatan->lokasi) }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label-admin">Deskripsi</label>
                <textarea name="deskripsi" class="form-control form-control-admin" rows="5">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label-admin">Foto Cover</label>
                @if($kegiatan->foto_cover)
                    <div class="mb-2">
                        <img src="{{ Storage::url($kegiatan->foto_cover) }}" style="max-height:160px;border-radius:12px;object-fit:cover;" alt="Cover">
                        <div class="text-muted mt-1" style="font-size:0.78rem;">Foto saat ini. Upload baru untuk mengganti.</div>
                    </div>
                @endif
                <input type="file" name="foto_cover" class="form-control form-control-admin" accept="image/*">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Update Kegiatan</button>
                <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
