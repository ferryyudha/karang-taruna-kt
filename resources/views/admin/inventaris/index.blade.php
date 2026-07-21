@extends('admin.layouts.app')
@section('title','Inventaris')
@section('page-title','Inventaris & Perlengkapan')
@section('breadcrumb','Admin / Inventaris')
@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Daftar Inventaris</h5>
        <small class="text-muted">{{ $inventaris->total() }} barang terdaftar</small>
    </div>
    <a href="{{ route('admin.inventaris.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-circle me-1"></i>Tambah Barang
    </a>
</div>

{{-- Filter --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Cari nama / kode barang..." value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="kategori_id" class="form-select">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $kat)
                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="kondisi" class="form-select">
            <option value="">Semua Kondisi</option>
            <option value="baik" {{ request('kondisi')=='baik' ? 'selected':'' }}>Baik</option>
            <option value="rusak_ringan" {{ request('kondisi')=='rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
            <option value="rusak_berat" {{ request('kondisi')=='rusak_berat' ? 'selected':'' }}>Rusak Berat</option>
        </select>
    </div>
    <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn-primary-custom w-100"><i class="bi bi-search"></i></button>
        <a href="{{ route('admin.inventaris.index') }}" class="btn btn-secondary btn-sm w-100">Reset</a>
    </div>
</form>

{{-- Tabel --}}
<div class="ui-card">
    <div class="table-responsive">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Tersedia</th>
                    <th class="text-center">Kondisi</th>
                    <th>Lokasi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventaris as $item)
                <tr>
                    <td><code class="text-purple">{{ $item->kode ?? '-' }}</code></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($item->foto)
                                <img src="{{ Storage::url($item->foto) }}" alt="" class="rounded-2 object-fit-cover" style="width:36px;height:36px;">
                            @else
                                <div class="rounded-2 bg-light d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                                    <i class="bi bi-box text-muted"></i>
                                </div>
                            @endif
                            <span class="fw-semibold">{{ $item->nama }}</span>
                        </div>
                    </td>
                    <td>{{ $item->kategori?->nama ?? '-' }}</td>
                    <td class="text-center">{{ $item->jumlah_total }}</td>
                    <td class="text-center">
                        <span class="badge-soft {{ $item->jumlah_tersedia > 0 ? 'badge-soft--success' : 'badge-soft--danger' }}">
                            {{ $item->jumlah_tersedia }}
                        </span>
                    </td>
                    <td class="text-center">
                        @php $kondisiVariant = match($item->kondisi){ 'baik'=>'success','rusak_ringan'=>'warning','rusak_berat'=>'danger',default=>'neutral' }; @endphp
                        <span class="badge-soft badge-soft--{{ $kondisiVariant }}">{{ $item->kondisi_label }}</span>
                    </td>
                    <td>{{ $item->lokasi ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.inventaris.edit', $item) }}" class="btn btn-sm btn-warning me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.inventaris.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="bi bi-box-seam"></i>
                            <h6>Belum ada data inventaris</h6>
                            <p>Tambah barang inventaris baru untuk mulai mencatat perlengkapan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inventaris->hasPages())
    <div class="p-3">{{ $inventaris->links() }}</div>
    @endif
</div>
@endsection
