@extends('admin.layouts.app')
@section('title','Kategori Inventaris')
@section('page-title','Kategori Barang')
@section('breadcrumb','Admin / Inventaris / Kategori')
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    {{-- Form Tambah --}}
    <div class="col-md-4">
        <div class="card-admin">
            <div class="card-header-admin"><h6 class="mb-0 fw-bold"><i class="bi bi-plus me-2"></i>Tambah Kategori</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.inventaris.kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-admin">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-admin @error('nama') is-invalid @enderror"
                            value="{{ old('nama') }}" required placeholder="e.g. Elektronik, Furniture...">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label-admin">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control form-control-admin" placeholder="Opsional...">{{ old('keterangan') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100"><i class="bi bi-plus-circle me-1"></i>Tambah</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="col-md-8">
        <div class="card-admin">
            <div class="card-header-admin"><h6 class="mb-0 fw-bold">Daftar Kategori ({{ $kategori->total() }})</h6></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Nama</th><th>Keterangan</th><th class="text-center">Jml Barang</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $kat)
                        <tr>
                            <td class="fw-semibold">{{ $kat->nama }}</td>
                            <td class="text-muted">{{ $kat->keterangan ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $kat->inventaris_count }}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editKat{{ $kat->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.inventaris.kategori.destroy', $kat) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editKat{{ $kat->id }}" tabindex="-1">
                            <div class="modal-dialog"><div class="modal-content">
                                <div class="modal-header"><h6 class="modal-title fw-bold">Edit Kategori</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <form action="{{ route('admin.inventaris.kategori.update', $kat) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label-admin">Nama <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control form-control-admin" value="{{ $kat->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-admin">Keterangan</label>
                                            <textarea name="keterangan" rows="2" class="form-control form-control-admin">{{ $kat->keterangan }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn-primary-custom">Simpan</button>
                                    </div>
                                </form>
                            </div></div>
                        </div>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($kategori->hasPages())
            <div class="p-3">{{ $kategori->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
