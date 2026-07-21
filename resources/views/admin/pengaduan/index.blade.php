@extends('admin.layouts.app')
@section('title', 'Kelola Pengaduan Warga')
@section('page-title', 'Pengaduan & Aspirasi Warga')
@section('breadcrumb', 'Admin / Pengaduan')

@section('content')
{{-- Stat Cards --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-card__label">Total Laporan Masuk</div>
                    <div class="stat-card__number text-primary mt-1">{{ $totalCount }}</div>
                    <small class="text-muted">Semua laporan warga</small>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-inbox"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-card__label">Diterima (Baru)</div>
                    <div class="stat-card__number text-warning mt-1">{{ $diterimaCount }}</div>
                    <small class="badge-soft badge-soft--warning mt-1"><i class="bi bi-clock me-1"></i>Perlu Tindakan</small>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-card__label">Sedang Diproses</div>
                    <div class="stat-card__number text-info mt-1">{{ $diprosesCount }}</div>
                    <small class="badge-soft badge-soft--info mt-1"><i class="bi bi-gear me-1"></i>Tindak Lapangan</small>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-tools"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-card__label">Selesai Ditangani</div>
                    <div class="stat-card__number text-success mt-1">{{ $selesaiCount }}</div>
                    <small class="badge-soft badge-soft--success mt-1"><i class="bi bi-check-circle me-1"></i>Tuntas</small>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check2-all"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Table Card --}}
<div class="ui-card">
    <div class="card-header bg-white p-3 border-bottom">
        <form method="GET" action="{{ route('admin.pengaduan.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3 col-6">
                <label class="form-label">Filter Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="diterima" {{ $status == 'diterima' ? 'selected' : '' }}>Diterima (Menunggu)</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 col-6">
                <label class="form-label">Filter Kategori</label>
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($daftarKategori as $key => $label)
                    <option value="{{ $key }}" {{ $kategori == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 col-8">
                <label class="form-label">Cari Kode / Pelapor / Judul</label>
                <input type="text" name="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ $search }}">
            </div>
            <div class="col-md-2 col-4 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary w-100 rounded-3">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="ui-table">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Pelapor & Kontak</th>
                        <th>Kategori & Judul</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($pengaduanList as $p)
                <tr>
                    <td>
                        <span class="badge-soft badge-soft--neutral font-monospace px-2 py-1 fs-6 fw-bold">
                            {{ $p->kode_tiket }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $p->nama_pelapor }}</div>
                        <small class="text-muted"><i class="bi bi-whatsapp text-success me-1"></i>{{ $p->phone_pelapor }}</small>
                    </td>
                    <td>
                        <span class="badge-soft badge-soft--neutral mb-1">{{ $p->nama_kategori }}</span>
                        <div class="fw-semibold text-dark">{{ $p->judul }}</div>
                    </td>
                    <td>
                        <small class="text-secondary"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($p->lokasi, 25) }}</small>
                    </td>
                    <td>
                        @if($p->status === 'diterima')
                            <span class="badge-soft badge-soft--warning"><i class="bi bi-clock me-1"></i>Diterima</span>
                        @elseif($p->status === 'diproses')
                            <span class="badge-soft badge-soft--info"><i class="bi bi-gear me-1"></i>Diproses</span>
                        @elseif($p->status === 'selesai')
                            <span class="badge-soft badge-soft--success"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                        @else
                            <span class="badge-soft badge-soft--danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                        @endif
                    </td>
                    <td>
                        <small class="text-muted">{{ $p->created_at->format('d/m/Y H:i') }}</small>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            {{-- Tombol Detail & Tanggapi --}}
                            <button type="button" class="btn btn-sm btn-primary rounded-2 px-2 py-1"
                                onclick="openModalDetail({{ json_encode($p) }})" title="Tindak Lanjut & Tanggapi">
                                <i class="bi bi-pencil-square me-1"></i>Tanggapi
                            </button>

                            {{-- Tombol WA Pelapor --}}
                            @if($p->wa_pelapor_link)
                            <a href="{{ $p->wa_pelapor_link }}" target="_blank" class="btn btn-sm btn-outline-success rounded-2 px-2 py-1" title="Chat Pelapor">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            @endif

                            {{-- Tombol Hapus --}}
                            <form method="POST" action="{{ route('admin.pengaduan.destroy', $p->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan pengaduan ini?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete px-2 py-1" title="Hapus Laporan">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h6>Belum ada pengaduan</h6>
                            <p>Belum ada data laporan pengaduan warga.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white py-3 border-top">
        {{ $pengaduanList->links() }}
    </div>
</div>

{{-- MODAL DETAIL & TANGGAPI PENGADUAN --}}
<div class="modal fade" id="modalTanggapiPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-clipboard-check me-2 text-primary"></i>Tindak Lanjut Pengaduan Warga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTanggapi" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body py-3">
                    {{-- Detail Laporan --}}
                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="row g-2 mb-2">
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Kode Tiket</small>
                                <strong class="font-monospace text-primary fs-6" id="mKodeTiket">-</strong>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Kategori</small>
                                <span class="badge-soft badge-soft--neutral" id="mKategori">-</span>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Pelapor & WA</small>
                                <strong id="mPelapor">-</strong>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Lokasi Kejadian</small>
                                <span id="mLokasi">-</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Judul Laporan</small>
                            <h6 class="fw-bold text-dark mb-0" id="mJudul">-</h6>
                        </div>
                        <div>
                            <small class="text-muted d-block">Isi Deskripsi Laporan</small>
                            <div class="p-2 bg-white rounded border small mt-1" id="mIsiLaporan">-</div>
                        </div>
                        <div id="mContainerFotoBukti" class="mt-2 d-none">
                            <small class="text-muted d-block mb-1">Foto Bukti Pelapor:</small>
                            <img id="mFotoBukti" src="" style="max-height:160px;border-radius:8px;" alt="Bukti Pelapor">
                        </div>
                    </div>

                    {{-- Form Tanggapan Admin --}}
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-pencil me-1"></i>Pembaruan Status & Tanggapan Pengurus</h6>

                    <div class="mb-3">
                        <label class="form-label">Status Penanganan <span class="text-danger">*</span></label>
                        <select name="status" id="mStatusSelect" class="form-select" required>
                            <option value="diterima">Diterima (Menunggu Penanganan)</option>
                            <option value="diproses">Diproses (Pengurus Sedang Tindak Lanjut)</option>
                            <option value="selesai">Selesai (Masalah Telah Ditangani)</option>
                            <option value="ditolak">Ditolak (Laporan Tidak Valid)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggapan / Catatan Pengurus untuk Warga</label>
                        <textarea name="tanggapan" id="mTanggapanText" class="form-control" rows="3" placeholder="Contoh: Tim kerja bakti Karang Taruna telah menambal jalan rusak pada hari Minggu..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Foto Penanganan / Hasil Lapangan <small class="text-muted">(Opsional)</small></label>
                        <div id="mContainerFotoPenanganan" class="mb-2 d-none">
                            <img id="mFotoPenanganan" src="" style="max-height:140px;border-radius:8px;" alt="Hasil Penanganan">
                            <div class="small text-muted mt-1">Foto penanganan saat ini. Upload baru untuk mengganti.</div>
                        </div>
                        <input type="file" name="foto_penanganan" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom px-4">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModalDetail(pengaduan) {
    var actionUrl = "{{ url('/admin/pengaduan') }}/" + pengaduan.id;
    document.getElementById('formTanggapi').action = actionUrl;

    document.getElementById('mKodeTiket').innerText = pengaduan.kode_tiket;
    document.getElementById('mKategori').innerText = pengaduan.nama_kategori;
    document.getElementById('mPelapor').innerText = pengaduan.nama_pelapor + ' (' + pengaduan.phone_pelapor + ')';
    document.getElementById('mLokasi').innerText = pengaduan.lokasi;
    document.getElementById('mJudul').innerText = pengaduan.judul;
    document.getElementById('mIsiLaporan').innerText = pengaduan.isi_laporan;

    document.getElementById('mStatusSelect').value = pengaduan.status;
    document.getElementById('mTanggapanText').value = pengaduan.tanggapan ? pengaduan.tanggapan : '';

    var containerBukti = document.getElementById('mContainerFotoBukti');
    if (pengaduan.foto_bukti) {
        document.getElementById('mFotoBukti').src = '/storage/' + pengaduan.foto_bukti;
        containerBukti.classList.remove('d-none');
    } else {
        containerBukti.classList.add('d-none');
    }

    var containerHasil = document.getElementById('mContainerFotoPenanganan');
    if (pengaduan.foto_penanganan) {
        document.getElementById('mFotoPenanganan').src = '/storage/' + pengaduan.foto_penanganan;
        containerHasil.classList.remove('d-none');
    } else {
        containerHasil.classList.add('d-none');
    }

    var modal = new bootstrap.Modal(document.getElementById('modalTanggapiPengaduan'));
    modal.show();
}
</script>
@endpush
