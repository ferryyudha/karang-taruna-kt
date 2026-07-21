@extends('admin.layouts.app')
@section('title', 'Manajemen Kas')
@section('page-title', 'Manajemen Akun Kas')
@section('breadcrumb', 'Admin / Keuangan / Kas')

@section('content')
<div class="row g-4">
    {{-- Form Tambah Kas --}}
    <div class="col-lg-4">
        <div class="card-admin">
            <div class="card-header">
                <h6 class="mb-0 fw-bold" id="formTitle"><i class="bi bi-cash-coin me-2 text-primary"></i>Akun Kas Baru</h6>
            </div>
            <div class="card-body">
                <form id="kasForm" method="POST" action="{{ route('admin.keuangan.kas.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="mb-3">
                        <label class="form-label-admin">Nama Akun Kas <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="kasNama" class="form-control form-control-admin" placeholder="Kas Utama, Rekening Bank, dll." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-admin">Keterangan</label>
                        <input type="text" name="keterangan" id="kasKeterangan" class="form-control form-control-admin" placeholder="Keterangan singkat...">
                    </div>

                    <div class="mb-4" id="saldoField">
                        <label class="form-label-admin">Saldo Awal</label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background:#E2E8F0;font-weight:600;">Rp</span>
                            <input type="number" name="saldo" class="form-control form-control-admin" placeholder="0" min="0">
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size:0.75rem;">Saldo awal hanya bisa ditentukan saat pembuatan akun kas pertama kali.</small>
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

    {{-- Daftar Akun Kas --}}
    <div class="col-lg-8">
        <div class="card-admin">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-list-task text-primary me-2"></i>Daftar Kas & Saldo</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Nama Akun Kas</th>
                            <th>Keterangan</th>
                            <th>Total Saldo</th>
                            <th>Total Transaksi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($kas as $k)
                    <tr>
                        <td class="fw-semibold">{{ $k->nama }}</td>
                        <td>{{ $k->keterangan ?? '-' }}</td>
                        <td class="fw-bold text-primary">Rp{{ number_format($k->saldo, 0, ',', '.') }}</td>
                        <td>{{ $k->transaksi_count }} transaksi</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn-edit" 
                                    onclick="editKas({{ $k->id }}, '{{ $k->nama }}', '{{ $k->keterangan }}', '{{ route('admin.keuangan.kas.update', $k) }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($k->transaksi_count == 0)
                                <form method="POST" action="{{ route('admin.keuangan.kas.destroy', $k) }}" onsubmit="return confirm('Hapus akun Kas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada akun kas didaftarkan.</td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editKas(id, nama, keterangan, url) {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-fill me-2 text-warning"></i>Edit Akun Kas';
    document.getElementById('kasNama').value = nama;
    document.getElementById('kasKeterangan').value = keterangan === 'null' ? '' : keterangan;
    
    // Hide Saldo Field during edit
    document.getElementById('saldoField').style.display = 'none';

    const form = document.getElementById('kasForm');
    form.action = url;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('btnSubmit').innerHTML = '<i class="bi bi-check-lg me-1"></i>Update';
    document.getElementById('btnCancel').classList.remove('d-none');
}

function resetForm() {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-cash-coin me-2 text-primary"></i>Akun Kas Baru';
    document.getElementById('kasNama').value = '';
    document.getElementById('kasKeterangan').value = '';
    
    // Show Saldo Field back
    document.getElementById('saldoField').style.display = 'block';

    const form = document.getElementById('kasForm');
    form.action = "{{ route('admin.keuangan.kas.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('btnSubmit').innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan';
    document.getElementById('btnCancel').classList.add('d-none');
}
</script>
@endpush
