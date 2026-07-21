@extends('admin.layouts.app')
@section('title','Tambah Kegiatan')
@section('page-title','Tambah Kegiatan')
@section('breadcrumb','Admin / Kegiatan / Tambah')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2 text-primary"></i>Form Kegiatan Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kegiatan.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label-admin">Nama Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control form-control-admin @error('nama') is-invalid @enderror"
                    value="{{ old('nama') }}" placeholder="Nama kegiatan..." required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label-admin">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control form-control-admin"
                        value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control form-control-admin"
                        value="{{ old('lokasi') }}" placeholder="Balai Desa, Lapangan...">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label-admin">Deskripsi</label>
                <textarea name="deskripsi" class="form-control form-control-admin" rows="5"
                    placeholder="Deskripsi kegiatan...">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label-admin">Foto Cover</label>
                <input type="file" name="foto_cover" class="form-control form-control-admin"
                    accept="image/*" onchange="previewImg(this,'coverPreview')">
                <img id="coverPreview" src="" alt="" style="display:none;max-height:180px;border-radius:12px;margin-top:10px;object-fit:cover;">
                <div class="text-muted mt-1" style="font-size:0.8rem;">Format: JPG, PNG, WebP. Maks 2MB.</div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Simpan Kegiatan</button>
                <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
@push('scripts')
<script>
function previewImg(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
