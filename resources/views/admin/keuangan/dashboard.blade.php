@extends('admin.layouts.app')
@section('title', 'Dashboard Keuangan')
@section('page-title', 'Dashboard Keuangan')
@section('breadcrumb', 'Admin / Keuangan / Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card stat-card--info">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="badge-soft badge-soft--info">Kas Aktif</span>
            </div>
            <div class="stat-card__number">Rp{{ number_format($saldoTotal, 0, ',', '.') }}</div>
            <div class="stat-card__label">Total Saldo Kas</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-card--success">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </div>
                <span class="badge-soft badge-soft--success">Akumulatif</span>
            </div>
            <div class="stat-card__number text-success">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="stat-card__label">Total Pemasukan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card stat-card--danger">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </div>
                <span class="badge-soft badge-soft--danger">Akumulatif</span>
            </div>
            <div class="stat-card__number text-danger">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="stat-card__label">Total Pengeluaran</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Saldo Akun Kas --}}
    <div class="col-lg-4">
        <div class="ui-card h-100">
            <div class="card-header bg-white border-bottom p-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-cash-coin text-primary me-2"></i>Rincian Saldo Kas</h6>
            </div>
            <div class="card-body p-0">
                @forelse($kasList as $k)
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div>
                        <div class="fw-semibold text-dark fs-6">{{ $k->nama }}</div>
                        <small class="text-muted">{{ $k->keterangan ?? 'Tanpa keterangan' }}</small>
                    </div>
                    <div class="fw-bold text-dark fs-6">
                        Rp{{ number_format($k->saldo, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bi bi-wallet2"></i>
                    <h6>Belum ada akun Kas</h6>
                    <p>Silakan tambahkan akun kas terlebih dahulu.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Chart Keuangan --}}
    <div class="col-lg-8">
        <div class="ui-card h-100">
            <div class="card-header bg-white border-bottom p-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line text-primary me-2"></i>Grafik Arus Kas {{ date('Y') }}</h6>
            </div>
            <div class="card-body p-3">
                <canvas id="arusKasChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="col-12">
        <div class="ui-card">
            <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Transaksi Terbaru</h6>
                <a href="{{ route('admin.keuangan.laporan.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Laporan Lengkap</a>
            </div>
            <div class="card-body p-0">
                @if($recentTransactions->count() > 0)
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Kategori</th>
                            <th>Kas</th>
                            <th>Tipe</th>
                            <th class="text-end">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($recentTransactions as $t)
                    <tr>
                        <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $t->keterangan ?? '-' }}</td>
                        <td>{{ $t->kategori->nama ?? '-' }}</td>
                        <td>{{ $t->kas->nama ?? '-' }}</td>
                        <td>
                            <span class="badge-soft badge-soft--{{ $t->tipe === 'pemasukan' ? 'success' : 'danger' }}">
                                {{ ucfirst($t->tipe) }}
                            </span>
                        </td>
                        <td class="text-end fw-semibold text-{{ $t->tipe === 'pemasukan' ? 'success' : 'danger' }}">
                            {{ $t->tipe === 'pemasukan' ? '+' : '-' }}Rp{{ number_format($t->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="bi bi-clock-history"></i>
                    <h6>Belum ada transaksi</h6>
                    <p>Transaksi transaksi baru akan muncul di sini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('arusKasChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Pemasukan',
                data: @json($pemasukanData),
                borderColor: '#16A34A',
                backgroundColor: 'rgba(22,163,74,0.05)',
                borderWidth: 3,
                tension: 0.35,
                fill: true
            },
            {
                label: 'Pengeluaran',
                data: @json($penguluranData ?? $pengeluaranData),
                borderColor: '#DC2626',
                backgroundColor: 'rgba(220,38,38,0.05)',
                borderWidth: 3,
                tension: 0.35,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { grid: { color: 'rgba(0,0,0,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
