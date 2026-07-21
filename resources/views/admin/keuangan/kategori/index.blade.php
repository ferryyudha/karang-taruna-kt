@extends('admin.layouts.app')
@section('title', 'Kategori Keuangan')
@section('page-title', 'Kategori Keuangan')
@section('breadcrumb', 'Admin / Keuangan / Kategori')

@section('content')
<div class="row g-4">
    {{-- Form Tambah Kategori --}}
    <div class="col-lg-4">
        <div class="card-admin">
            <div class="card-header">
                <h6 class="mb-0 fw-bold" id="formTitle"><i class="bi bi-tag-fill me-2 text-primary"></i>Kategori Baru</h6>
            </div>
            <div class="card-body">
                <form id="kategoriForm" method="POST" action="{{ route('admin.keuangan.kategori.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="mb-3">
                        <label class="form-label-admin">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="kategoriNama" class="form-control form-control-admin" placeholder="Iuran Bulanan, Konsumsi, Transportasi..." required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-admin">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select name="tipe" id="kategoriTipe" class="form-select form-select-admin" required>
                            <option value="pemasukan">Pemasukan (Uang Masuk)</option>
                            <option value="pengeluaran">Pengeluaran (Uang Keluar)</option>
                        </select>
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

    {{-- Daftar Kategori --}}
    <div class="col-lg-8">
        <div class="card-admin">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-tags text-primary me-2"></i>Daftar Kategori</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-admin mb-0">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Tipe</th>
                            <th>Jumlah Transaksi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($kategori as $k)
                    <tr>
                        <td class="fw-semibold">{{ $k->nama }}</td>
                        <td>
                            <span class="badge-{{ $k->tipe === 'pemasukan' ? 'publish' : 'draft' }}">
                                {{ $k->tipe === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td>{{ $k->transaksi_count }} transaksi</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn-edit" 
                                    onclick="editKategori({{ $k->id }}, '{{ $k->nama }}', '{{ $k->tipe }}', '{{ route('admin.keuangan.kategori.update', $k) }}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($k->transaksi_count == 0)
                                <form method="POST" action="{{ route('admin.keuangan.kategori.destroy', $k) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada kategori keuangan dibuat.</td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($kategori->hasPages())
        <div class="mt-3">{{ $kategori->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function editKategori(id, nama, tipe, url) {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-pencil-fill me-2 text-warning"></i>Edit Kategori';
    document.getElementById('kategoriNama').value = nama;
    document.getElementById('kategoriTipe').value = tipe;
    
    const form = document.getElementById('kategoriForm');
    form.action = url;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('btnSubmit').innerHTML = '<i class="bi bi-check-lg me-1"></i>Update';
    document.getElementById('btnCancel').classList.remove('d-none');
}

function resetForm() {
    document.getElementById('formTitle').innerHTML = '<i class="bi bi-tag-fill me-2 text-primary"></i>Kategori Baru';
    document.getElementById('kategoriNama').value = '';
    document.getElementById('kategoriTipe').value = 'pemasukan';
    
    const form = document.getElementById('kategoriForm');
    form.action = "{{ route('admin.keuangan.kategori.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('btnSubmit').innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan';
    document.getElementById('btnCancel').classList.add('d-none');
}
</script>
@endpush
