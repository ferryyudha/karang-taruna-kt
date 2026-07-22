@extends('admin.layouts.app')
@section('title', $polling->judul)
@section('page-title', 'Polling')
@section('breadcrumb', 'Polling Anggota / Detail')
@section('content')

<div class="row g-4 justify-content-center">
    <div class="col-lg-8">

        {{-- Card Polling --}}
        <div class="ui-card mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge-soft badge-soft--{{ $polling->status_variant }}">{{ $polling->status_label }}</span>
                    <span class="badge-soft badge-soft--neutral">{{ $polling->tipe === 'single' ? '1 Pilihan' : 'Multi Pilihan' }}</span>
                </div>
                <h4 class="fw-bold text-dark mb-2">{{ $polling->judul }}</h4>
                @if($polling->deskripsi)
                    <p class="text-muted mb-3">{{ $polling->deskripsi }}</p>
                @endif
                <div class="small text-muted d-flex flex-wrap gap-3">
                    <span><i class="bi bi-calendar3 me-1"></i>{{ $polling->mulai_at->format('d M Y H:i') }} — {{ $polling->selesai_at->format('d M Y H:i') }}</span>
                    <span><i class="bi bi-people me-1"></i>{{ $totalVotes }} total suara · {{ $polling->total_voter }} pemilih</span>
                </div>
            </div>
        </div>

        {{-- FORM VOTE — Tampilkan jika belum vote & polling aktif --}}
        @if(!$sudahVote && $isAktif)
        <div class="ui-card mb-4">
            <div class="card-header bg-white p-4 border-bottom">
                <h6 class="fw-bold mb-0"><i class="bi bi-hand-index-thumb text-primary me-2"></i>Pilih Suara Anda</h6>
                @if($polling->tipe === 'multi')
                    <small class="text-muted">Anda boleh memilih lebih dari satu opsi.</small>
                @else
                    <small class="text-muted">Pilih satu opsi terbaik menurut Anda.</small>
                @endif
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-3 mb-3">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.anggota.polling.vote', $polling) }}" method="POST" id="formVote">
                    @csrf
                    <div class="stack-md mb-4" id="opsiGroup">
                        @foreach($polling->opsi as $opsi)
                        <label class="opsi-label d-flex align-items-center gap-3 p-3 rounded-3 border cursor-pointer"
                            style="cursor:pointer; transition: all 0.2s;"
                            for="opsi_{{ $opsi->id }}">
                            <input
                                type="{{ $polling->tipe === 'single' ? 'radio' : 'checkbox' }}"
                                id="opsi_{{ $opsi->id }}"
                                name="{{ $polling->tipe === 'single' ? 'opsi_id' : 'opsi_id[]' }}"
                                value="{{ $opsi->id }}"
                                class="form-check-input vote-input mt-0 flex-shrink-0"
                                style="width:20px;height:20px;">
                            <span class="fw-semibold text-dark">{{ $opsi->teks_opsi }}</span>
                        </label>
                        @endforeach
                    </div>
                    <button type="submit" id="btnSubmitVote" class="btn-primary-custom w-100" disabled>
                        <i class="bi bi-send-fill me-2"></i>Kirim Suara Saya
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- HASIL VOTE — Tampilkan jika sudah vote ATAU polling selesai --}}
        @if($sudahVote || !$isAktif)
        @if(session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success"></i> {{ session('success') }}
            </div>
        @endif

        <div class="ui-card mb-4">
            <div class="card-header bg-white p-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                    {{ $sudahVote ? 'Pilihan Anda & Hasil Sementara' : 'Hasil Polling' }}
                </h6>
                <span class="small text-muted">{{ $totalVotes }} total suara</span>
            </div>
            <div class="card-body p-4">
                @if($totalVotes === 0)
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h6>Belum ada suara</h6>
                        <p>Jadilah yang pertama memberikan suara!</p>
                    </div>
                @else
                    <div class="stack-md">
                        @foreach($polling->opsi->sortByDesc(fn($o) => $o->jumlah_vote) as $opsi)
                        @php
                            $dipilih = $pilihanUser->contains($opsi->id);
                            $persen  = $opsi->persentase;
                        @endphp
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold text-dark d-flex align-items-center gap-2">
                                    @if($dipilih)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @endif
                                    {{ $opsi->teks_opsi }}
                                    @if($dipilih)
                                        <span class="badge-soft badge-soft--success small">Pilihan Anda</span>
                                    @endif
                                </span>
                                <span class="small fw-bold text-primary">{{ $opsi->jumlah_vote }} suara ({{ $persen }}%)</span>
                            </div>
                            <div class="progress rounded-pill" style="height:10px;background:#F1F5F9;">
                                <div class="progress-bar rounded-pill"
                                    style="width:{{ $persen }}%;background:{{ $dipilih ? '#16A34A' : '#4154F1' }};transition:width 0.6s ease;"
                                    role="progressbar" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Polling belum dimulai --}}
        @if(!$sudahVote && !$isAktif && $polling->status === 'aktif' && now()->lt($polling->mulai_at))
        <div class="ui-card mb-4">
            <div class="empty-state">
                <i class="bi bi-hourglass-split"></i>
                <h6>Polling belum dimulai</h6>
                <p>Polling ini akan dibuka pada {{ $polling->mulai_at->format('d M Y, H:i') }} WIB.</p>
            </div>
        </div>
        @endif

        <a href="{{ route('admin.anggota.polling') }}" class="btn btn-light rounded-3 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Polling
        </a>
    </div>
</div>

@push('scripts')
<script>
    const inputs = document.querySelectorAll('.vote-input');
    const btnSubmit = document.getElementById('btnSubmitVote');
    const labels = document.querySelectorAll('.opsi-label');

    if (inputs.length && btnSubmit) {
        inputs.forEach(inp => {
            inp.addEventListener('change', () => {
                const checked = document.querySelectorAll('.vote-input:checked');
                btnSubmit.disabled = checked.length === 0;

                // Highlight selected
                labels.forEach(lbl => {
                    lbl.style.background = '';
                    lbl.style.borderColor = '';
                });
                checked.forEach(c => {
                    const lbl = c.closest('.opsi-label');
                    if (lbl) { lbl.style.background = '#EFF6FF'; lbl.style.borderColor = '#4154F1'; }
                });
            });
        });
    }
</script>
@endpush
@endsection
