@extends('admin.layouts.app')
@section('title','Tambah Barang')
@section('page-title','Tambah Barang Inventaris')
@section('breadcrumb','Admin / Inventaris / Tambah')
@section('content')
<div class="card-admin">
    <div class="card-header-admin">
        <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Form Tambah Barang</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.inventaris.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label-admin">Kode Barang <small class="text-muted">(opsional, auto-generate)</small></label>
                    <input type="text" name="kode" class="form-control form-control-admin @error('kode') is-invalid @enderror"
                        placeholder="e.g. INV-001" value="{{ old('kode') }}">
                    @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label-admin">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control form-control-admin @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Kategori</label>
                    <select name="kategori_id" class="form-select form-control-admin">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id')==$kat->id ? 'selected':'' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-admin">Jumlah Total <span class="text-danger">*</span></label>
                    <input type="number" name="jumlah_total" class="form-control form-control-admin @error('jumlah_total') is-invalid @enderror"
                        value="{{ old('jumlah_total', 0) }}" min="0" required>
                    @error('jumlah_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label-admin">Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" class="form-select form-control-admin" required>
                        <option value="baik" {{ old('kondisi','baik')=='baik' ? 'selected':'' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi')=='rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi')=='rusak_berat' ? 'selected':'' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Lokasi Penyimpanan</label>
                    <input type="text" name="lokasi" class="form-control form-control-admin" value="{{ old('lokasi') }}" placeholder="e.g. Gudang Sekretariat">
                </div>
                <div class="col-md-3">
                    <label class="form-label-admin">Tanggal Pengadaan</label>
                    <input type="date" name="tanggal_pengadaan" class="form-control form-control-admin" value="{{ old('tanggal_pengadaan') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label-admin">Harga Satuan (Rp)</label>
                    <input type="number" name="harga_satuan" class="form-control form-control-admin" value="{{ old('harga_satuan') }}" min="0" step="100" placeholder="0">
                </div>
                <div class="col-12">
                    <label class="form-label-admin">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-control form-control-admin" placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Foto Barang</label>
                    <input type="file" name="foto" class="form-control form-control-admin @error('foto') is-invalid @enderror" accept="image/*">
                    <small class="text-muted">JPG, PNG, WebP. Auto-resize maks 800×800px.</small>
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Simpan Barang</button>
                <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
