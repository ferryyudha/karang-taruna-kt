@extends('admin.layouts.app')
@section('title','Edit Lomba')
@section('page-title','Edit Lomba')
@section('breadcrumb','Admin / Lomba / Edit')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card-admin">
    <div class="card-header-admin">
        <h6 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-primary"></i>Edit Lomba</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.lomba.update', $lomba) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label-admin">Kegiatan Induk <span class="text-danger">*</span></label>
                <select name="kegiatan_id" class="form-select form-control-admin @error('kegiatan_id') is-invalid @enderror" required>
                    @foreach($kegiatanList as $k)
                        <option value="{{ $k->id }}" {{ old('kegiatan_id', $lomba->kegiatan_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }} ({{ $k->tanggal->format('d M Y') }})</option>
                    @endforeach
                </select>
                @error('kegiatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label-admin">Nama Lomba <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control form-control-admin @error('nama') is-invalid @enderror"
                    value="{{ old('nama', $lomba->nama) }}" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label-admin">Kategori Peserta</label>
                <input type="text" name="kategori" class="form-control form-control-admin" value="{{ old('kategori', $lomba->kategori) }}">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label-admin">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control form-control-admin @error('tanggal') is-invalid @enderror"
                        value="{{ old('tanggal', $lomba->tanggal->format('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" class="form-control form-control-admin"
                        value="{{ old('waktu_mulai', $lomba->waktu_mulai ? \Carbon\Carbon::parse($lomba->waktu_mulai)->format('H:i') : '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control form-control-admin" value="{{ old('lokasi', $lomba->lokasi) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label-admin">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab" class="form-control form-control-admin" value="{{ old('penanggung_jawab', $lomba->penanggung_jawab) }}">
            </div>

            <div class="mb-4">
                <label class="form-label-admin">Deskripsi / Aturan Lomba</label>
                <textarea name="deskripsi" class="form-control form-control-admin" rows="4">{{ old('deskripsi', $lomba->deskripsi) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
                <a href="{{ route('admin.lomba.show', $lomba) }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
