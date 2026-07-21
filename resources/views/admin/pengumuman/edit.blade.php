@extends('admin.layouts.app')
@section('title','Edit Pengumuman')
@section('page-title','Edit Pengumuman')
@section('breadcrumb','Admin / Pengumuman / Edit')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-warning"></i>Edit Pengumuman</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pengumuman.update', $pengumuman) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label-admin">Judul Pengumuman <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control form-control-admin @error('judul') is-invalid @enderror"
                    value="{{ old('judul', $pengumuman->judul) }}" required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label-admin">Kategori</label>
                    <input type="text" name="kategori" class="form-control form-control-admin"
                        value="{{ old('kategori', $pengumuman->kategori) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control form-control-admin @error('tanggal') is-invalid @enderror"
                        value="{{ old('tanggal', $pengumuman->tanggal->format('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label-admin">Isi Pengumuman <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control form-control-admin @error('isi') is-invalid @enderror"
                    rows="8" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label-admin">Status</label>
                <select name="status" class="form-select form-select-admin">
                    <option value="draft" {{ $pengumuman->status=='draft'?'selected':'' }}>Draft</option>
                    <option value="publish" {{ $pengumuman->status=='publish'?'selected':'' }}>Publish</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Update Pengumuman</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
