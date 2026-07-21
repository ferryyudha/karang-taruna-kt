@extends('admin.layouts.app')
@section('title','Tambah Pengumuman')
@section('page-title','Tambah Pengumuman')
@section('breadcrumb','Admin / Pengumuman / Tambah')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-megaphone me-2 text-primary"></i>Form Pengumuman Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pengumuman.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label-admin">Judul Pengumuman <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control form-control-admin @error('judul') is-invalid @enderror"
                    value="{{ old('judul') }}" placeholder="Masukkan judul pengumuman..." required>
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label-admin">Kategori</label>
                    <input type="text" name="kategori" class="form-control form-control-admin"
                        value="{{ old('kategori') }}" placeholder="Umum, Sosial, Kegiatan...">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control form-control-admin @error('tanggal') is-invalid @enderror"
                        value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label-admin">Isi Pengumuman <span class="text-danger">*</span></label>
                <textarea name="isi" class="form-control form-control-admin @error('isi') is-invalid @enderror"
                    rows="8" placeholder="Tulis isi pengumuman di sini..." required>{{ old('isi') }}</textarea>
                @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label-admin">Status</label>
                <select name="status" class="form-select form-select-admin">
                    <option value="draft" {{ old('status')=='draft'?'selected':'' }}>Draft</option>
                    <option value="publish" {{ old('status')=='publish'?'selected':'' }}>Publish</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Simpan Pengumuman</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
