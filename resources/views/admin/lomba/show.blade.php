@extends('admin.layouts.app')
@section('title', $lomba->nama)
@section('page-title', $lomba->nama)
@section('breadcrumb','Admin / Lomba / '.$lomba->nama)
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Info Lomba --}}
<div class="card-admin mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <span class="badge bg-{{ $lomba->status_color }} mb-2">{{ $lomba->status_label }}</span>
                <h5 class="fw-bold mb-1">{{ $lomba->nama }}</h5>
                <div class="text-muted" style="font-size:0.85rem;">
                    <i class="bi bi-collection me-1"></i>{{ $lomba->kegiatan->nama }}
                    @if($lomba->kategori) &nbsp;·&nbsp; <i class="bi bi-people me-1"></i>{{ $lomba->kategori }} @endif
                </div>
            </div>
            <a href="{{ route('admin.lomba.edit', $lomba) }}" class="btn-edit"><i class="bi bi-pencil me-1"></i>Edit</a>
        </div>
        <hr>
        <div class="row g-3" style="font-size:0.85rem;">
            <div class="col-md-3"><i class="bi bi-calendar3 me-1 text-muted"></i>{{ $lomba->tanggal->format('d M Y') }}</div>
            <div class="col-md-3"><i class="bi bi-clock me-1 text-muted"></i>{{ $lomba->waktu_mulai ? \Carbon\Carbon::parse($lomba->waktu_mulai)->format('H:i') : '-' }}</div>
            <div class="col-md-3"><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $lomba->lokasi ?? '-' }}</div>
            <div class="col-md-3"><i class="bi bi-person-badge me-1 text-muted"></i>{{ $lomba->penanggung_jawab ?? '-' }}</div>
        </div>
        @if($lomba->deskripsi)
        <div class="mt-3 text-muted" style="font-size:0.85rem;white-space:pre-wrap;">{{ $lomba->deskripsi }}</div>
        @endif
    </div>
</div>

<div class="row g-4">
    {{-- CHECKLIST PERALATAN  --}}
    <div class="col-lg-7">
        <div class="card-admin">
            <div class="card-header-admin d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Checklist Peralatan</h6>
                <button class="btn-primary-custom" style="font-size:0.8rem;padding:6px 12px;" data-bs-toggle="modal" data-bs-target="#addPeralatan">
                    <i class="bi bi-plus-lg me-1"></i>Tambah
                </button>
            </div>
            <div class="card-body pb-2">
                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:0.78rem;">
                    <span class="text-muted">Progress Kesiapan</span>
                    <span class="fw-semibold">{{ $lomba->peralatan_progress }}%</span>
                </div>
                <div class="progress" style="height:8px;border-radius:8px;">
                    <div class="progress-bar bg-success" style="width:{{ $lomba->peralatan_progress }}%"></div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>Nama Alat</th><th class="text-center">Jml</th><th>Status</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($lomba->peralatan as $p)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:0.85rem;">{{ $p->nama_alat }}</div>
                                @if($p->inventaris)
                                <div class="text-muted" style="font-size:0.75rem;"><i class="bi bi-link-45deg"></i>Stok gudang: {{ $p->inventaris->jumlah_tersedia }} tersedia</div>
                                @endif
                                @if($p->catatan)
                                <div class="text-muted" style="font-size:0.75rem;">{{ $p->catatan }}</div>
                                @endif
                            </td>
                            <td class="text-center">{{ $p->jumlah_dibutuhkan }}</td>
                            <td><span class="badge bg-{{ $p->status_color }}" style="font-size:0.72rem;">{{ $p->status_label }}</span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editPeralatan{{ $p->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('admin.lomba.peralatan.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus item ini dari checklist?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit Peralatan --}}
                        <div class="modal fade" id="editPeralatan{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog"><div class="modal-content">
                                <div class="modal-header"><h6 class="modal-title fw-bold">Edit Peralatan</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                <form action="{{ route('admin.lomba.peralatan.update', $p) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label-admin">Kaitkan ke Inventaris (opsional)</label>
                                            <select name="inventaris_id" class="form-select form-control-admin">
                                                <option value="">-- Tidak dikaitkan --</option>
                                                @foreach($inventarisList as $inv)
                                                <option value="{{ $inv->id }}" {{ $p->inventaris_id == $inv->id ? 'selected' : '' }}>{{ $inv->nama }} (tersedia: {{ $inv->jumlah_tersedia }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-admin">Nama Alat <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_alat" class="form-control form-control-admin" value="{{ $p->nama_alat }}" required>
                                        </div>
                                        <div class="row g-3 mb-3">
                                            <div class="col-6">
                                                <label class="form-label-admin">Jumlah</label>
                                                <input type="number" name="jumlah_dibutuhkan" class="form-control form-control-admin" value="{{ $p->jumlah_dibutuhkan }}" min="1" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label-admin">Status</label>
                                                <select name="status" class="form-select form-control-admin">
                                                    <option value="perlu_beli" {{ $p->status=='perlu_beli'?'selected':'' }}>Perlu Dibeli</option>
                                                    <option value="perlu_pinjam" {{ $p->status=='perlu_pinjam'?'selected':'' }}>Perlu Dipinjam</option>
                                                    <option value="tersedia" {{ $p->status=='tersedia'?'selected':'' }}>Tersedia di Gudang</option>
                                                    <option value="siap" {{ $p->status=='siap'?'selected':'' }}>Siap Dipakai</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-admin">Catatan</label>
                                            <textarea name="catatan" class="form-control form-control-admin" rows="2">{{ $p->catatan }}</textarea>
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
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada peralatan di checklist.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PESERTA & JUARA  --}}
    <div class="col-lg-5">
        <div class="card-admin">
            <div class="card-header-admin d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-people me-2 text-primary"></i>Peserta</h6>
                <button class="btn-primary-custom" style="font-size:0.8rem;padding:6px 12px;" data-bs-toggle="modal" data-bs-target="#addPeserta">
                    <i class="bi bi-plus-lg me-1"></i>Tambah
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>No</th><th>Peserta</th><th>Juara</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($lomba->peserta as $p)
                        <tr>
                            <td style="font-size:0.85rem;">{{ $p->nomor_urut ?? '-' }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:0.85rem;">{{ $p->nama_peserta }}</div>
                                @if($p->kategori_usia)<div class="text-muted" style="font-size:0.75rem;">{{ $p->kategori_usia }}</div>@endif
                            </td>
                            <td>
                                @if($p->juara)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill me-1"></i>{{ $p->juara }}</span>
                                @else
                                    <span class="text-muted" style="font-size:0.78rem;">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editPeserta{{ $p->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('admin.lomba.peserta.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus peserta ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit Peserta --}}
                        <div class="modal fade" id="editPeserta{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog"><div class="modal-content">
                                <div class="modal-header"><h6 class="modal-title fw-bold">Edit Peserta</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                <form action="{{ route('admin.lomba.peserta.update', $p) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label-admin">Nama Peserta/Tim <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_peserta" class="form-control form-control-admin" value="{{ $p->nama_peserta }}" required>
                                        </div>
                                        <div class="row g-3 mb-3">
                                            <div class="col-6">
                                                <label class="form-label-admin">Nomor Urut</label>
                                                <input type="text" name="nomor_urut" class="form-control form-control-admin" value="{{ $p->nomor_urut }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label-admin">Kategori Usia</label>
                                                <input type="text" name="kategori_usia" class="form-control form-control-admin" value="{{ $p->kategori_usia }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-admin">Kontak</label>
                                            <input type="text" name="kontak" class="form-control form-control-admin" value="{{ $p->kontak }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label-admin">Hasil / Juara</label>
                                            <select name="juara" class="form-select form-control-admin">
                                                <option value="">Belum ada hasil</option>
                                                <option value="Juara 1" {{ $p->juara=='Juara 1'?'selected':'' }}>Juara 1</option>
                                                <option value="Juara 2" {{ $p->juara=='Juara 2'?'selected':'' }}>Juara 2</option>
                                                <option value="Juara 3" {{ $p->juara=='Juara 3'?'selected':'' }}>Juara 3</option>
                                                <option value="Harapan 1" {{ $p->juara=='Harapan 1'?'selected':'' }}>Harapan 1</option>
                                                <option value="Harapan 2" {{ $p->juara=='Harapan 2'?'selected':'' }}>Harapan 2</option>
                                            </select>
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
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada peserta terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Peralatan --}}
<div class="modal fade" id="addPeralatan" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h6 class="modal-title fw-bold">Tambah Peralatan</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <form action="{{ route('admin.lomba.peralatan.store', $lomba) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-admin">Kaitkan ke Inventaris (opsional)</label>
                    <select name="inventaris_id" class="form-select form-control-admin">
                        <option value="">-- Tidak dikaitkan / belum ada --</option>
                        @foreach($inventarisList as $inv)
                        <option value="{{ $inv->id }}">{{ $inv->nama }} (tersedia: {{ $inv->jumlah_tersedia }})</option>
                        @endforeach
                    </select>
                    <div class="text-muted mt-1" style="font-size:0.78rem;">Kalau alatnya udah ada di gudang inventaris, pilih di sini biar kelihatan stoknya.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label-admin">Nama Alat <span class="text-danger">*</span></label>
                    <input type="text" name="nama_alat" class="form-control form-control-admin" placeholder="Karung goni, bendera start, dll..." required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label-admin">Jumlah</label>
                        <input type="number" name="jumlah_dibutuhkan" class="form-control form-control-admin" value="1" min="1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-admin">Status</label>
                        <select name="status" class="form-select form-control-admin">
                            <option value="perlu_beli">Perlu Dibeli</option>
                            <option value="perlu_pinjam">Perlu Dipinjam</option>
                            <option value="tersedia">Tersedia di Gudang</option>
                            <option value="siap">Siap Dipakai</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-admin">Catatan</label>
                    <textarea name="catatan" class="form-control form-control-admin" rows="2" placeholder="Opsional..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-primary-custom">Tambahkan</button>
            </div>
        </form>
    </div></div>
</div>

{{-- Modal Tambah Peserta --}}
<div class="modal fade" id="addPeserta" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h6 class="modal-title fw-bold">Tambah Peserta</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <form action="{{ route('admin.lomba.peserta.store', $lomba) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-admin">Nama Peserta/Tim <span class="text-danger">*</span></label>
                    <input type="text" name="nama_peserta" class="form-control form-control-admin" placeholder="Nama individu atau nama tim..." required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label-admin">Nomor Urut</label>
                        <input type="text" name="nomor_urut" class="form-control form-control-admin" placeholder="01">
                    </div>
                    <div class="col-6">
                        <label class="form-label-admin">Kategori Usia</label>
                        <input type="text" name="kategori_usia" class="form-control form-control-admin" placeholder="Anak-anak...">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-admin">Kontak</label>
                    <input type="text" name="kontak" class="form-control form-control-admin" placeholder="No. HP (opsional)">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-primary-custom">Tambahkan</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
