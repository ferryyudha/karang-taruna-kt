@extends('admin.layouts.app')
@section('title','Edit Anggota')
@section('page-title','Edit Anggota')
@section('breadcrumb','Admin / Anggota / Edit')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-warning"></i>Edit Anggota — {{ $anggota->nama }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.anggota.update', $anggota) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label-admin">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control form-control-admin" value="{{ old('nama', $anggota->nama) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="jabatan" class="form-control form-control-admin" value="{{ old('jabatan', $anggota->jabatan) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Periode</label>
                    <input type="text" name="periode" class="form-control form-control-admin" value="{{ old('periode', $anggota->periode) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">No. HP</label>
                    <input type="text" name="phone" class="form-control form-control-admin" value="{{ old('phone', $anggota->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control form-control-admin" value="{{ old('urutan', $anggota->urutan) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Email</label>
                    <input type="email" name="email" class="form-control form-control-admin" value="{{ old('email', $anggota->email) }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch ms-2">
                        <input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1" {{ $anggota->aktif ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="aktif" style="font-size:0.85rem;">Anggota Aktif</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label-admin">Bio</label>
                    <textarea name="bio" class="form-control form-control-admin" rows="3">{{ old('bio', $anggota->bio) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label-admin">Foto</label>
                    @if($anggota->foto)
                    <div class="mb-2 d-flex align-items-center gap-3">
                        <img src="{{ Storage::url($anggota->foto) }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;" alt="Foto">
                        <small class="text-muted">Upload baru untuk mengganti foto.</small>
                    </div>
                    @endif
                    <input type="file" name="foto" class="form-control form-control-admin" accept="image/*">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Update Anggota</button>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
