@extends('admin.layouts.app')
@section('title','Peminjaman Inventaris')
@section('page-title','Peminjaman Barang')
@section('breadcrumb','Admin / Inventaris / Peminjaman')
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="row g-4">
    {{-- Form Peminjaman --}}
    <div class="col-lg-4">
        <div class="ui-card">
            <div class="card-header bg-white p-3 border-bottom"><h6 class="mb-0 fw-bold"><i class="bi bi-plus me-2"></i>Catat Peminjaman</h6></div>
            <div class="card-body p-3">
                <form action="{{ route('admin.inventaris.peminjaman.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Barang <span class="text-danger">*</span></label>
                        <select name="inventaris_id" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($inventarisList as $brg)
                                <option value="{{ $brg->id }}">{{ $brg->nama }} (Tersedia: {{ $brg->jumlah_tersedia }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Peminjam <span class="text-danger">*</span></label>
                        <input type="text" name="peminjam" class="form-control" value="{{ old('peminjam') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Kontak</label>
                        <input type="text" name="kontak" class="form-control" value="{{ old('kontak') }}" placeholder="No. HP...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Rencana Kembali <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ old('tanggal_kembali_rencana') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control" placeholder="Opsional...">{{ old('keterangan') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100"><i class="bi bi-arrow-right-circle me-1"></i>Simpan Peminjaman</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Peminjaman --}}
    <div class="col-lg-8">
        <div class="ui-card">
            <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Daftar Peminjaman</h6>
                <form method="GET" class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="dipinjam" {{ request('status')=='dipinjam' ? 'selected':'' }}>Dipinjam</option>
                        <option value="terlambat" {{ request('status')=='terlambat' ? 'selected':'' }}>Terlambat</option>
                        <option value="dikembalikan" {{ request('status')=='dikembalikan' ? 'selected':'' }}>Dikembalikan</option>
                    </select>
                </form>
            </div>
            <div class="table-responsive">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Peminjam</th>
                            <th class="text-center">Jml</th>
                            <th>Tgl Pinjam</th>
                            <th>Rencana Kembali</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $p)
                        <tr>
                            <td class="fw-semibold">{{ $p->inventaris->nama ?? '-' }}</td>
                            <td>
                                {{ $p->peminjam }}
                                @if($p->kontak)<br><small class="text-muted">{{ $p->kontak }}</small>@endif
                            </td>
                            <td class="text-center">{{ $p->jumlah }}</td>
                            <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @php $sv = match($p->status){ 'dipinjam'=>'warning','terlambat'=>'danger','dikembalikan'=>'success',default=>'neutral' }; @endphp
                                <span class="badge-soft badge-soft--{{ $sv }}">{{ $p->status_label }}</span>
                                @if($p->tanggal_kembali_aktual)
                                    <br><small class="text-muted">Kembali: {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->status !== 'dikembalikan')
                                <form action="{{ route('admin.inventaris.peminjaman.kembalikan', $p) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Tandai barang sudah dikembalikan?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success mb-1" title="Kembalikan"><i class="bi bi-check-lg"></i></button>
                                </form>
                                @endif
                                <form action="{{ route('admin.inventaris.peminjaman.destroy', $p) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus data peminjaman ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-clipboard-x"></i>
                                    <h6>Belum ada data peminjaman</h6>
                                    <p>Catat peminjaman barang inventaris menggunakan form di samping.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($peminjaman->hasPages())
            <div class="p-3">{{ $peminjaman->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
