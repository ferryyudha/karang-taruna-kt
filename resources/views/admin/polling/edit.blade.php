@extends('admin.layouts.app')
@section('title','Edit Polling')
@section('page-title','Edit Polling')
@section('breadcrumb','Admin / Polling / Edit')
@section('content')

@if($hasVotes)
<div class="alert alert-warning border-0 rounded-3 mb-4 d-flex align-items-start gap-3">
    <i class="bi bi-exclamation-triangle-fill fs-5 text-warning flex-shrink-0 mt-1"></i>
    <div>
        <strong>Polling sudah memiliki vote masuk.</strong><br>
        <span class="small">Opsi yang ada tidak dapat diedit atau dihapus, namun Anda bisa menambah opsi baru.</span>
    </div>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="ui-card">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Polling</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.polling.update', $polling) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Judul Polling <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul', $polling->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $polling->deskripsi) }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tipe Voting</label>
                            <select name="tipe" class="form-select" {{ $hasVotes ? 'disabled' : '' }}>
                                <option value="single" {{ old('tipe',$polling->tipe)==='single' ? 'selected':'' }}>Single — Pilih 1 opsi</option>
                                <option value="multi"  {{ old('tipe',$polling->tipe)==='multi'  ? 'selected':'' }}>Multi — Boleh pilih banyak</option>
                            </select>
                            @if($hasVotes)<input type="hidden" name="tipe" value="{{ $polling->tipe }}">@endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft"   {{ old('status',$polling->status)==='draft'   ? 'selected':'' }}>Draft</option>
                                <option value="aktif"   {{ old('status',$polling->status)==='aktif'   ? 'selected':'' }}>Aktif</option>
                                <option value="selesai" {{ old('status',$polling->status)==='selesai' ? 'selected':'' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="mulai_at" class="form-control"
                                value="{{ old('mulai_at', $polling->mulai_at->format('Y-m-d\TH:i')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="selesai_at" class="form-control"
                                value="{{ old('selesai_at', $polling->selesai_at->format('Y-m-d\TH:i')) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="tampil_publik" id="tampilPublik" class="form-check-input"
                                value="1" {{ old('tampil_publik', $polling->tampil_publik) ? 'checked' : '' }}>
                            <label for="tampilPublik" class="form-check-label fw-semibold small">
                                <i class="bi bi-globe me-1 text-info"></i>Tampilkan hasil ke publik
                            </label>
                        </div>
                    </div>

                    {{-- Daftar opsi yang sudah ada --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Opsi Pilihan Saat Ini</label>
                        <div class="stack-sm">
                            @foreach($polling->opsi as $i => $opsi)
                            <div class="d-flex align-items-center gap-2">
                                <input type="hidden" name="opsi_id[]" value="{{ $opsi->id }}">
                                <input type="text" name="opsi[]" class="form-control"
                                    value="{{ old("opsi.$i", $opsi->teks_opsi) }}"
                                    {{ $hasVotes ? 'readonly' : '' }}
                                    placeholder="Opsi pilihan...">
                                @if(!$hasVotes)
                                <span class="small text-muted text-nowrap">
                                    {{ $opsi->votes()->count() }} vote
                                </span>
                                @else
                                <span class="badge-soft badge-soft--neutral text-nowrap small">
                                    {{ $opsi->votes()->count() }} vote
                                </span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tambah opsi baru (selalu boleh) --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Tambah Opsi Baru <small class="text-muted">(opsional)</small></label>
                        <div id="opsiBaruContainer" class="stack-sm"></div>
                        <button type="button" id="btnTambahOpsiBaru" class="btn btn-outline-primary btn-sm rounded-3 mt-2">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Opsi Baru
                        </button>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.polling.index') }}" class="btn btn-light rounded-3 fw-semibold">Batal</a>
                        <a href="{{ route('admin.polling.hasil', $polling) }}" class="btn btn-outline-primary btn-sm rounded-3 ms-auto">
                            <i class="bi bi-bar-chart-fill me-1"></i>Lihat Hasil
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="ui-card p-4">
            <h6 class="fw-bold mb-3">Ringkasan</h6>
            <div class="stack-sm small">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Voter</span>
                    <strong>{{ $polling->total_voter }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Total Vote</span>
                    <strong>{{ $polling->votes()->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Jumlah Opsi</span>
                    <strong>{{ $polling->opsi->count() }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let opsiBaruCount = 0;
    const container = document.getElementById('opsiBaruContainer');

    document.getElementById('btnTambahOpsiBaru').addEventListener('click', () => {
        opsiBaruCount++;
        const div = document.createElement('div');
        div.className = 'd-flex align-items-center gap-2';
        div.innerHTML = `
            <input type="text" name="opsi_baru[]" class="form-control" placeholder="Opsi baru ${opsiBaruCount}...">
            <button type="button" class="btn btn-sm btn-outline-danger rounded-3" onclick="this.closest('div').remove()">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
    });
</script>
@endpush
@endsection
