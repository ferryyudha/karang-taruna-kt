@extends('admin.layouts.app')
@section('title','Edit Barang')
@section('page-title','Edit Barang Inventaris')
@section('breadcrumb','Admin / Inventaris / Edit')
@section('content')
<div class="card-admin">
    <div class="card-header-admin">
        <h6 class="mb-0 fw-bold"><i class="bi bi-pencil me-2"></i>Edit — {{ $inventaris->nama }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.inventaris.update', $inventaris) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label-admin">Kode Barang</label>
                    <input type="text" name="kode" class="form-control form-control-admin"
                        value="{{ $inventaris->kode }}" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                </div>
                <div class="col-md-8">
                    <label class="form-label-admin">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control form-control-admin @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $inventaris->nama) }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Kategori</label>
                    <select name="kategori_id" class="form-select form-control-admin">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id', $inventaris->kategori_id)==$kat->id ? 'selected':'' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-admin">Jumlah Total <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_total" class="form-control form-control-admin" value="{{ old('jumlah_total', $inventaris->jumlah_total) }}" min="0" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label-admin">Jumlah Tersedia <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_tersedia" class="form-control form-control-admin" value="{{ old('jumlah_tersedia', $inventaris->jumlah_tersedia) }}" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" class="form-select form-control-admin" required>
                        <option value="baik" {{ old('kondisi',$inventaris->kondisi)=='baik' ? 'selected':'' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi',$inventaris->kondisi)=='rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi',$inventaris->kondisi)=='rusak_berat' ? 'selected':'' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control form-control-admin" value="{{ old('lokasi', $inventaris->lokasi) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Tanggal Pengadaan</label>
                    <input type="date" name="tanggal_pengadaan" class="form-control form-control-admin" value="{{ old('tanggal_pengadaan', $inventaris->tanggal_pengadaan?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label-admin">Harga Satuan (Rp)</label>
                    <input type="number" name="harga_satuan" class="form-control form-control-admin" value="{{ old('harga_satuan', $inventaris->harga_satuan) }}" min="0" step="100">
                </div>
                <div class="col-12">
                    <label class="form-label-admin">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-control form-control-admin">{{ old('keterangan', $inventaris->keterangan) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Foto Barang</label>
                    @if($inventaris->foto)
                        <div class="mb-2">
                            <img src="{{ Storage::url($inventaris->foto) }}" alt="foto" style="height:80px;border-radius:8px;object-fit:cover;">
                        </div>
                    @endif
                    <input type="file" name="foto" class="form-control form-control-admin @error('foto') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Update Barang</button>
                <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
