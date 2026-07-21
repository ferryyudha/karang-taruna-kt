@extends('admin.layouts.app')
@section('title','Tambah Anggota')
@section('page-title','Tambah Anggota')
@section('breadcrumb','Admin / Anggota / Tambah')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Form Anggota Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.anggota.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label-admin">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control form-control-admin" value="{{ old('nama') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="jabatan" class="form-control form-control-admin" value="{{ old('jabatan') }}" placeholder="Ketua, Sekretaris..." required>
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Periode</label>
                    <input type="text" name="periode" class="form-control form-control-admin" value="{{ old('periode') }}" placeholder="2023-2025">
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">No. HP</label>
                    <input type="text" name="phone" class="form-control form-control-admin" value="{{ old('phone') }}" placeholder="081234567890">
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control form-control-admin" value="{{ old('urutan', 0) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Email</label>
                    <input type="email" name="email" class="form-control form-control-admin" value="{{ old('email') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch ms-2">
                        <input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" {{ old('aktif', 1) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="aktif" style="font-size:0.85rem;">Anggota Aktif</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label-admin">Bio / Keterangan</label>
                    <textarea name="bio" class="form-control form-control-admin" rows="3" placeholder="Tentang anggota...">{{ old('bio') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label-admin">Foto</label>
                    <input type="file" name="foto" class="form-control form-control-admin" accept="image/*" onchange="previewImg(this,'fotoPreview')">
                    <img id="fotoPreview" src="" alt="" style="display:none;max-height:150px;border-radius:50%;margin-top:10px;width:150px;height:150px;object-fit:cover;">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Simpan Anggota</button>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
@push('scripts')
<script>
function previewImg(input, id) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { const img = document.getElementById(id); img.src = e.target.result; img.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
