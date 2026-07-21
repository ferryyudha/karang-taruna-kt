@extends('admin.layouts.app')
@section('title', 'Pemasukan Keuangan')
@section('page-title', 'Catatan Pemasukan (Kas Masuk)')
@section('breadcrumb', 'Admin / Keuangan / Pemasukan')

@section('content')
<div class="row g-4">
    {{-- Form Tambah/Edit Pemasukan --}}
    <div class="col-lg-4">
        <div class="card-admin">
            <div class="card-header">
                <h6 class="mb-0 fw-bold" id="formTitle"><i class="bi bi-arrow-down-left-circle me-2 text-success"></i>Pencatatan Uang Masuk</h6>
            </div>
            <div class="card-body">
                <form id="pemasukanForm" method="POST" action="{{ route('admin.keuangan.pemasukan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="mb-3">
                        <label class="form-label-admin">Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" id="transaksiTanggal" class="form-control form-control-admin" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-admin">Pilih Akun Kas <span class="text-danger">*</span></label>
                        <select name="kas_id" id="transaksiKas" class="form-select form-select-admin" required>
                            <option value="">-- Pilih Kas --</option>
                            @foreach($kasList as $kas)
                            <option value="{{ $kas->id }}">
                                {{ $kas->nama }} (Saldo: Rp{{ number_format($kas->saldo, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-admin">Kategori Pemasukan <span class="text-danger">*</span></label>
                        <select name="kategori_id" id="transaksiKategori" class="form-select form-select-admin" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-admin">Nominal (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background:#E2E8F0;font-weight:600;">Rp</span>
                            <input type="number" name="jumlah" id="transaksiJumlah" class="form-control form-control-admin" placeholder="0" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-admin">Keterangan / Catatan</label>
                        <textarea name="keterangan" id="transaksiKeterangan" class="form-control form-control-admin" rows="3" placeholder="Pemasukan dari..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-admin">Bukti Transaksi <small class="text-muted">(Foto/Nota)</small></label>
                        <div id="currentBukti" class="mb-2 d-none">
                            <img id="buktiPreview" src="" style="max-height:100px;border-radius:8px;" alt="Bukti">
                            <div class="text-muted mt-1" style="font-size:0.75rem;">Bukti saat ini. Upload baru jika ingin mengganti.</div>
                        </div>
                        <input type="file" name="bukti_foto" class="form-control form-control-admin" accept="image/*">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom w-100" id="btnSubmit">
                            <i class="bi bi-check-lg me-1"></i>Simpan
                        </button>
                        <button type="button" id="btnCancel" class="btn btn-light rounded-3 d-none" onclick="resetForm()">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Pemasukan --}}
    <div class="col-lg-8">
        <div class="card-admin">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-task text-primary me-2"></i>Daftar Pemasukan</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Kas</th>
                            <th>Keterangan</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pemasukan as $p)
                    <tr>
                        <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $p->kategori->nama ?? '-' }}</td>
                        <td>{{ $p->kas->nama ?? '-' }}</td>
                        <td>
                            {{ $p->keterangan ?? '-' }}
                            @if($p->bukti_foto)
                            <a href="{{ Storage::url($p->bukti_foto) }}" target="_blank" class="ms-1 text-primary" title="Lihat Bukti Foto">
                                <i class="bi bi-file-earmark-image"></i>
                            </a>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-success">Rp{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn-edit" 
                                    onclick="editPemasukan({{ $p->id }}, '{{ $p->tanggal->format('Y-m-d') }}', {{ $p->kas_id }}, {{ $p->kategori_id }}, {{ $p->jumlah }}, '{{ $p->keterangan }}', '{{ $p->bukti_foto ? Storage::url($p->bukti_foto) : '' }}', '{{ route('admin.keuangan.pemasukan.update', $p) }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.keuangan.pemasukan.destroy', $p) }}" onsubmit="return confirm('Hapus catatan pemasukan ini? Tindakan ini akan mengurangi saldo kas terkait.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada pemasukan dicatat.</td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pemasukan->hasPages())
        <div class="mt-3">{{ $pemasukan->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function editPemasukan(id, tanggal, kasId, kategoriId, jumlah, keterangan, buktiUrl, url) {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-fill me-2 text-warning"></i>Edit Uang Masuk';
    document.getElementById('transaksiTanggal').value = tanggal;
    document.getElementById('transaksiKas').value = kasId;
    document.getElementById('transaksiKategori').value = kategoriId;
    document.getElementById('transaksiJumlah').value = jumlah;
    document.getElementById('transaksiKeterangan').value = keterangan === 'null' ? '' : keterangan;
    
    // Show/hide current bukti image preview
    const previewDiv = document.getElementById('currentBukti');
    if (buktiUrl) {
        document.getElementById('buktiPreview').src = buktiUrl;
        previewDiv.classList.remove('d-none');
    } else {
        previewDiv.classList.add('d-none');
    }

    const form = document.getElementById('pemasukanForm');
    form.action = url;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('btnSubmit').innerHTML = '<i class="bi bi-check-lg me-1"></i>Update';
    document.getElementById('btnCancel').classList.remove('d-none');
}

function resetForm() {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-arrow-down-left-circle me-2 text-success"></i>Pencatatan Uang Masuk';
    document.getElementById('transaksiTanggal').value = "{{ date('Y-m-d') }}";
    document.getElementById('transaksiKas').value = '';
    document.getElementById('transaksiKategori').value = '';
    document.getElementById('transaksiJumlah').value = '';
    document.getElementById('transaksiKeterangan').value = '';
    document.getElementById('currentBukti').classList.add('d-none');

    const form = document.getElementById('pemasukanForm');
    form.action = "{{ route('admin.keuangan.pemasukan.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('btnSubmit').innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan';
    document.getElementById('btnCancel').classList.add('d-none');
}
</script>
@endpush
