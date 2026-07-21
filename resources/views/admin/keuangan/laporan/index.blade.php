@extends('admin.layouts.app')
@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan & Buku Kas Umum')
@section('breadcrumb', 'Admin / Keuangan / Laporan')

@section('content')
{{-- Filter Laporan --}}
<div class="card-admin mb-4" id="filterCard">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-filter-right me-2 text-primary"></i>Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.keuangan.laporan.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-2.5 col-lg-2">
                    <label class="form-label-admin">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control form-control-admin" value="{{ $startDate }}">
                </div>
                <div class="col-md-2.5 col-lg-2">
                    <label class="form-label-admin">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control form-control-admin" value="{{ $endDate }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label-admin">Tipe Transaksi</label>
                    <select name="tipe" class="form-select form-select-admin">
                        <option value="">-- Semua --</option>
                        <option value="pemasukan" {{ $tipe == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ $tipe == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-2.5">
                    <label class="form-label-admin">Akun Kas</label>
                    <select name="kas_id" class="form-select form-select-admin">
                        <option value="">-- Semua Kas --</option>
                        @foreach($kasList as $kas)
                        <option value="{{ $kas->id }}" {{ $kasId == $kas->id ? 'selected' : '' }}>{{ $kas->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2.5">
                    <label class="form-label-admin">Kategori</label>
                    <select name="kategori_id" class="form-select form-select-admin">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ $kategoriId == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }} ({{ ucfirst($kat->tipe) }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mt-3 d-flex gap-2">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.keuangan.laporan.index') }}" class="btn btn-light rounded-3">Reset</a>
                    
                    {{-- Export & Print Buttons --}}
                    @if($transaksi->count() > 0)
                    <a href="{{ route('admin.keuangan.laporan.export', request()->all()) }}" class="btn btn-success rounded-3 ms-auto d-inline-flex align-items-center gap-1" style="font-weight:600;font-size:0.88rem;">
                        <i class="bi bi-file-earmark-excel"></i> Ekspor ke Excel (CSV)
                    </a>
                    <button type="button" onclick="printReport()" class="btn btn-primary rounded-3 d-inline-flex align-items-center gap-1" style="font-weight:600;font-size:0.88rem;background:#4154F1;">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Lembar Laporan Buku Kas Umum (Spreadsheet Style) --}}
<div class="card-admin" id="reportArea">
    <div class="card-header bg-white text-center py-4 border-0" id="reportHeader" style="display:none;">
        <h4 class="fw-bold mb-1 text-dark">BUKU KAS UMUM</h4>
        <h5 class="fw-semibold text-muted mb-2">KARANG TARUNA</h5>
        <div style="font-size:0.85rem;color:#64748B;">
            Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong>
        </div>
        <hr class="my-3 mx-auto" style="max-width:300px;border-top:2px solid #E2E8F0;">
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-admin mb-0" style="border: 1px solid #E2E8F0 !important;">
            <thead>
                <tr style="background:#F8FAFC;">
                    <th style="width:50px;" class="text-center">No</th>
                    <th style="width:120px;">Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>Akun Kas</th>
                    <th class="text-end" style="width:140px;">Pemasukan (Rp)</th>
                    <th class="text-end" style="width:140px;">Pengeluaran (Rp)</th>
                    <th class="text-end" style="width:160px;">Saldo Akumulatif</th>
                </tr>
            </thead>
            <tbody>
            @php $saldoAkumulatif = 0; @endphp
            @forelse($transaksi as $index => $t)
                @php
                    if ($t->tipe === 'pemasukan') {
                        $saldoAkumulatif += $t->jumlah;
                    } else {
                        $saldoAkumulatif -= $t->jumlah;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $t->keterangan ?? '-' }}</td>
                    <td>{{ $t->kategori->nama ?? '-' }}</td>
                    <td>{{ $t->kas->nama ?? '-' }}</td>
                    <td class="text-end fw-semibold text-success">
                        {!! $t->tipe === 'pemasukan' ? 'Rp' . number_format($t->jumlah, 0, ',', '.') : '-' !!}
                    </td>
                    <td class="text-end fw-semibold text-danger">
                        {!! $t->tipe === 'pengeluaran' ? 'Rp' . number_format($t->jumlah, 0, ',', '.') : '-' !!}
                    </td>
                    <td class="text-end fw-bold text-dark">
                        Rp{{ number_format($saldoAkumulatif, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="bi bi-file-earmark-x d-block mb-2" style="font-size:3rem;opacity:0.3;"></i>
                        Tidak ada transaksi ditemukan pada filter ini.
                    </td>
                </tr>
            @endforelse

            {{-- Summary footer row --}}
            @if($transaksi->count() > 0)
                <tr style="background:#F8FAFC;font-weight:700;border-top:2px solid #E2E8F0 !important;">
                    <td colspan="5" class="text-end">REKAP TOTAL :</td>
                    <td class="text-end text-success">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                    <td class="text-end text-danger">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    <td class="text-end text-primary" style="background:#EFF6FF;">Rp{{ number_format($saldoAkhir, 0, ',', '.') }}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    body * { visibility: hidden; }
    #reportArea, #reportArea * { visibility: visible; }
    #reportArea { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border: none; }
    #reportHeader { display: block !important; }
    #filterCard, .topbar, .sidebar, .sidebar-overlay, .btn, .btn-primary-custom, .btn-success, #btnCancel, .alert, .footer { display: none !important; }
    .main-content { margin-left: 0 !important; }
    .content-area { padding: 0 !important; }
}
</style>
@endpush

@push('scripts')
<script>
function printReport() {
    window.print();
}
</script>
@endpush
