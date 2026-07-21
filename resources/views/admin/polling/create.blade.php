@extends('admin.layouts.app')
@section('title','Buat Polling Baru')
@section('page-title','Buat Polling Baru')
@section('breadcrumb','Admin / Polling / Buat')
@section('content')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="ui-card">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle text-primary me-2"></i>Formulir Polling Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.polling.store') }}" method="POST" id="formPolling">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Judul Polling <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul') }}" placeholder="Contoh: Pilih Tema HUT RI ke-80..." required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi <small class="text-muted">(Opsional)</small></label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                            placeholder="Jelaskan konteks polling ini...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tipe Voting <span class="text-danger">*</span></label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                                <option value="single" {{ old('tipe','single')==='single' ? 'selected':'' }}>Single — Pilih 1 opsi saja</option>
                                <option value="multi" {{ old('tipe')==='multi' ? 'selected':'' }}>Multi — Boleh pilih lebih dari 1</option>
                            </select>
                            @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Status Awal</label>
                            <select name="status" class="form-select">
                                <option value="draft" {{ old('status','draft')==='draft' ? 'selected':'' }}>Draft (belum aktif)</option>
                                <option value="aktif" {{ old('status')==='aktif' ? 'selected':'' }}>Aktif (langsung bisa divote)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="mulai_at" class="form-control @error('mulai_at') is-invalid @enderror"
                                value="{{ old('mulai_at') }}" required>
                            @error('mulai_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="selesai_at" class="form-control @error('selesai_at') is-invalid @enderror"
                                value="{{ old('selesai_at') }}" required>
                            @error('selesai_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="tampil_publik" id="tampilPublik" class="form-check-input"
                                value="1" {{ old('tampil_publik') ? 'checked' : '' }}>
                            <label for="tampilPublik" class="form-check-label fw-semibold small">
                                <i class="bi bi-globe me-1 text-info"></i>Tampilkan hasil ke publik (setelah polling selesai)
                            </label>
                        </div>
                    </div>

                    {{-- Opsi Dinamis --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Opsi Pilihan <span class="text-danger">*</span> <small class="text-muted">(min. 2 opsi)</small></label>
                        <div id="opsiContainer" class="stack-sm">
                            @if(old('opsi'))
                                @foreach(old('opsi') as $o)
                                <div class="opsi-row d-flex align-items-center gap-2">
                                    <input type="text" name="opsi[]" class="form-control" value="{{ $o }}" placeholder="Opsi pilihan..." required>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3 btn-hapus-opsi" title="Hapus opsi">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="opsi-row d-flex align-items-center gap-2">
                                    <input type="text" name="opsi[]" class="form-control" placeholder="Opsi 1..." required>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3 btn-hapus-opsi" title="Hapus opsi" style="visibility:hidden;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="opsi-row d-flex align-items-center gap-2">
                                    <input type="text" name="opsi[]" class="form-control" placeholder="Opsi 2..." required>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-3 btn-hapus-opsi" title="Hapus opsi" style="visibility:hidden;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <button type="button" id="btnTambahOpsi" class="btn btn-outline-primary btn-sm rounded-3 mt-2">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Opsi
                        </button>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-send-fill me-2"></i>Simpan Polling
                        </button>
                        <a href="{{ route('admin.polling.index') }}" class="btn btn-light rounded-3 fw-semibold">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tips Sidebar --}}
    <div class="col-lg-4">
        <div class="ui-card p-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-lightbulb text-warning me-2"></i>Tips Polling</h6>
            <ul class="small text-muted ps-3 stack-sm">
                <li>Gunakan tipe <strong>Single</strong> untuk pilihan eksklusif (misal: pilih 1 tema).</li>
                <li>Gunakan tipe <strong>Multi</strong> jika anggota boleh pilih lebih dari satu opsi.</li>
                <li>Set status <strong>Draft</strong> terlebih dahulu untuk mengecek tampilan sebelum dipublikasikan.</li>
                <li>Opsi tidak dapat diedit setelah ada yang melakukan voting.</li>
                <li>Aktifkan <strong>Tampil Publik</strong> agar warga bisa melihat hasil di halaman publik.</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const container = document.getElementById('opsiContainer');
    const btnTambah = document.getElementById('btnTambahOpsi');

    function updateHapusVisibility() {
        const rows = container.querySelectorAll('.opsi-row');
        rows.forEach((row, i) => {
            const btn = row.querySelector('.btn-hapus-opsi');
            btn.style.visibility = rows.length <= 2 ? 'hidden' : 'visible';
        });
    }

    btnTambah.addEventListener('click', () => {
        const count = container.querySelectorAll('.opsi-row').length + 1;
        const div = document.createElement('div');
        div.className = 'opsi-row d-flex align-items-center gap-2';
        div.innerHTML = `
            <input type="text" name="opsi[]" class="form-control" placeholder="Opsi ${count}..." required>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-3 btn-hapus-opsi" title="Hapus opsi">
                <i class="bi bi-trash"></i>
            </button>
        `;
        div.querySelector('.btn-hapus-opsi').addEventListener('click', () => {
            div.remove();
            updateHapusVisibility();
        });
        container.appendChild(div);
        updateHapusVisibility();
    });

    container.querySelectorAll('.btn-hapus-opsi').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.opsi-row').remove();
            updateHapusVisibility();
        });
    });

    updateHapusVisibility();
</script>
@endpush
@endsection
