@extends('admin.layouts.app')
@section('title', 'Iuran Anggota / Kas Otomatis')
@section('page-title', 'Iuran Warga / Kas Otomatis')
@section('breadcrumb', 'Admin / Keuangan / Iuran Anggota')

@section('content')
<div class="row g-4 mb-4">
    {{-- Stat Cards --}}
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-card__label">Target Tagihan ({{ $daftarBulan[(int)$selectedBulan] ?? '' }} {{ $selectedTahun }})</div>
                    <div class="stat-card__number text-primary">Rp {{ number_format($totalNominalTagihan, 0, ',', '.') }}</div>
                    <small class="text-muted">{{ $totalTagihanCount }} Anggota Terdaftar</small>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-card__label">Total Terkumpul</div>
                    <div class="stat-card__number text-success">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</div>
                    <small class="badge-soft badge-soft--success mt-1"><i class="bi bi-check-circle-fill me-1"></i>{{ $totalLunasCount }} Lunas</small>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--danger">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-card__label">Belum Bayar</div>
                    <div class="stat-card__number text-danger">{{ $totalBelumBayarCount }} <span class="fs-6 text-muted fw-normal">Orang</span></div>
                    <small class="badge-soft badge-soft--danger mt-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Perlu Reminder</small>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-person-x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-card__label">Kelancaran Iuran</div>
                    <div class="stat-card__number text-info">{{ $persentaseLunas }}%</div>
                    <div class="progress mt-2" style="height: 6px; width: 100px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persentaseLunas }}%"></div>
                    </div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-pie-chart"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Card with Tabs --}}
<div class="ui-card">
    <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-3 py-3 border-bottom">
        {{-- Navigation Tabs --}}
        <ul class="nav nav-pills card-header-pills" id="iuranTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" id="tab-list-btn" data-bs-toggle="tab" data-bs-target="#tab-list" type="button" role="tab">
                    <i class="bi bi-list-check me-2"></i>Daftar Tagihan {{ $daftarBulan[(int)$selectedBulan] ?? '' }} {{ $selectedTahun }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" id="tab-matrix-btn" data-bs-toggle="tab" data-bs-target="#tab-matrix" type="button" role="tab">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Matriks Rekap Tahunan ({{ $selectedTahun }})
                </button>
            </li>
        </ul>

        {{-- Action Button --}}
        <div>
            <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalGenerateIuran">
                <i class="bi bi-plus-circle me-1"></i>Generate Tagihan Bulanan
            </button>
        </div>
    </div>

    <div class="card-body p-4">
        <div class="tab-content" id="iuranTabsContent">

            {{-- TAB 1: LIST TAGIHAN --}}
            <div class="tab-pane fade show active" id="tab-list" role="tabpanel">
                {{-- Filter Bar --}}
                <form method="GET" action="{{ route('admin.keuangan.iuran.index') }}" class="row g-2 mb-4 align-items-center">
                    <div class="col-md-2 col-6">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select" onchange="this.form.submit()">
                            @foreach($daftarBulan as $num => $nama)
                            <option value="{{ $num }}" {{ $selectedBulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-6">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                            @for($y = date('Y') + 1; $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ $selectedTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label">Status Pembayaran</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="semua" {{ $selectedStatus == 'semua' ? 'selected' : '' }}>Semua Status</option>
                            <option value="belum_bayar" {{ $selectedStatus == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="lunas" {{ $selectedStatus == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="form-label">Cari Anggota</label>
                        <input type="text" name="search" class="form-control" placeholder="Nama anggota..." value="{{ $search }}">
                    </div>
                    <div class="col-md-2 col-12 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100 rounded-3">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </form>

                {{-- Table Tagihan --}}
                <div class="table-responsive">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Periode</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th class="text-center">Aksi & Reminder</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($iuranList as $iuran)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;font-weight:600;">
                                            @if($iuran->anggota->foto ?? false)
                                                <img src="{{ Storage::url($iuran->anggota->foto) }}" class="rounded-circle" style="width:100%;height:100%;object-fit:cover;" alt="Foto">
                                            @else
                                                {{ strtoupper(substr($iuran->anggota->nama ?? 'A', 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $iuran->anggota->nama ?? '-' }}</div>
                                            <small class="text-muted">{{ $iuran->anggota->jabatan ?? 'Anggota' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-soft badge-soft--neutral">
                                        <i class="bi bi-calendar-event me-1"></i>{{ $iuran->nama_bulan }} {{ $iuran->tahun }}
                                    </span>
                                </td>
                                <td class="fw-bold text-dark">
                                    Rp {{ number_format($iuran->nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($iuran->status === 'lunas')
                                        <span class="badge-soft badge-soft--success"><i class="bi bi-check-circle me-1"></i>LUNAS</span>
                                    @elseif($iuran->status === 'belum_bayar')
                                        <span class="badge-soft badge-soft--danger"><i class="bi bi-clock me-1"></i>BELUM BAYAR</span>
                                    @else
                                        <span class="badge-soft badge-soft--neutral">DIBATALKAN</span>
                                    @endif
                                </td>
                                <td>
                                    @if($iuran->status === 'lunas')
                                        <div class="small text-dark fw-semibold"><i class="bi bi-wallet2 text-primary me-1"></i>{{ $iuran->kas->nama ?? 'Kas' }}</div>
                                        <small class="text-muted"><i class="bi bi-calendar-check me-1"></i>{{ $iuran->tanggal_bayar ? $iuran->tanggal_bayar->format('d/m/Y') : '-' }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        @if($iuran->status === 'belum_bayar')
                                            {{-- Tombol Bayar --}}
                                            <button type="button" class="btn btn-sm btn-success rounded-2 text-white px-2 py-1"
                                                onclick="openModalBayar({{ json_encode($iuran) }}, '{{ $iuran->anggota->nama }}')" title="Catat Pembayaran">
                                                <i class="bi bi-cash-coin me-1"></i>Bayar
                                            </button>

                                            {{-- Tombol Reminder WA --}}
                                            @if($iuran->wa_link)
                                                <a href="{{ $iuran->wa_link }}" target="_blank" class="btn btn-sm btn-outline-success rounded-2 px-2 py-1" title="Kirim Reminder WhatsApp">
                                                    <i class="bi bi-whatsapp"></i> WA
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-light text-muted rounded-2 px-2 py-1" disabled title="No HP Anggota belum diisi">
                                                    <i class="bi bi-whatsapp"></i> WA
                                                </button>
                                            @endif
                                        @else
                                            {{-- Tombol Batal Bayar --}}
                                            <form method="POST" action="{{ route('admin.keuangan.iuran.batal', $iuran->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan status lunas ini? Transaksi pada Kas akan dihapus.')" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning rounded-2 text-dark px-2 py-1" title="Batalkan Lunas">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Batal
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tombol Hapus Tagihan --}}
                                        <form method="POST" action="{{ route('admin.keuangan.iuran.destroy', $iuran->id) }}" onsubmit="return confirm('Hapus tagihan iuran ini?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete px-2 py-1" title="Hapus Tagihan">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <h6>Belum ada tagihan</h6>
                                        <p>Belum ada data tagihan iuran untuk periode <strong>{{ $daftarBulan[(int)$selectedBulan] ?? '' }} {{ $selectedTahun }}</strong>.</p>
                                        <div class="mt-3">
                                            <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalGenerateIuran">
                                                <i class="bi bi-plus-circle me-1"></i>Generate Tagihan Sekarang
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $iuranList->links() }}
                </div>
            </div>

            {{-- TAB 2: MATRIKS REKAP TAHUNAN --}}
            <div class="tab-pane fade" id="tab-matrix" role="tabpanel">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold mb-0 text-dark">Tabel Status Iuran Anggota Tahun {{ $selectedTahun }}</h6>
                    <small class="text-muted">
                        <span class="badge-soft badge-soft--success me-1">✓ Lunas</span>
                        <span class="badge-soft badge-soft--danger me-1">✕ Belum Bayar</span>
                        <span class="badge-soft badge-soft--neutral">- Belum Dibuat</span>
                    </small>
                </div>

                <div class="table-responsive">
                    <table class="ui-table text-center align-middle">
                        <thead>
                            <tr>
                                <th class="text-start" style="min-width:180px;">Nama Anggota</th>
                                @foreach($daftarBulan as $mNum => $mNama)
                                    <th style="width:70px;">{{ substr($mNama, 0, 3) }}</th>
                                @endforeach
                                <th style="width:90px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($anggotaMatrix as $ang)
                            @php
                                $lunasCount = 0;
                            @endphp
                            <tr>
                                <td class="text-start fw-semibold text-dark">
                                    {{ $ang->nama }}
                                </td>
                                @foreach($daftarBulan as $mNum => $mNama)
                                    @php
                                        $st = $matrixStatus[$ang->id][$mNum] ?? null;
                                        if ($st === 'lunas') $lunasCount++;
                                    @endphp
                                    <td>
                                        @if($st === 'lunas')
                                            <span class="badge-soft badge-soft--success rounded-circle p-2" title="Lunas"><i class="bi bi-check-lg"></i></span>
                                        @elseif($st === 'belum_bayar')
                                            <span class="badge-soft badge-soft--danger rounded-circle p-2" title="Belum Bayar"><i class="bi bi-x-lg"></i></span>
                                        @else
                                            <span class="text-muted fs-6">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="fw-bold text-primary">
                                    {{ $lunasCount }}/12
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14">
                                    <div class="empty-state">
                                        <i class="bi bi-people"></i>
                                        <h6>Belum ada data anggota</h6>
                                        <p>Data anggota belum tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL 1: GENERATE TAGIHAN BULANAN --}}
<div class="modal fade" id="modalGenerateIuran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg style-rounded">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Generate Tagihan Iuran Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.keuangan.iuran.generate') }}">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-muted small">
                        Fitur ini akan secara otomatis membuatkan tagihan iuran baru bagi <strong>seluruh anggota aktif</strong> untuk periode yang dipilih.
                    </p>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Bulan Tagihan <span class="text-danger">*</span></label>
                            <select name="bulan" class="form-select" required>
                                @foreach($daftarBulan as $num => $nama)
                                <option value="{{ $num }}" {{ $selectedBulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tahun Tagihan <span class="text-danger">*</span></label>
                            <select name="tahun" class="form-select" required>
                                @for($y = date('Y') + 1; $y >= 2024; $y--)
                                <option value="{{ $y }}" {{ $selectedTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nominal Per Anggota (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background:#E2E8F0;font-weight:600;">Rp</span>
                            <input type="number" name="nominal" class="form-control" value="10000" min="1000" step="1000" required>
                        </div>
                        <small class="text-muted">Contoh: Rp 10.000 / bulan</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Default Akun Kas Pemasukan</label>
                        <select name="kas_id" class="form-select">
                            <option value="">-- Pilih Kas Default (Opsional) --</option>
                            @foreach($kasList as $kas)
                            <option value="{{ $kas->id }}">{{ $kas->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Default Kategori Pemasukan</label>
                        <select name="kategori_id" class="form-select">
                            <option value="">-- Pilih Kategori Default (Opsional) --</option>
                            @foreach($kategoriPemasukanList as $kat)
                            <option value="{{ $kat->id }}" {{ str_contains(strtolower($kat->nama), 'iuran') ? 'selected' : '' }}>{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-play-fill me-1"></i>Generate Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 2: CATAT PEMBAYARAN IURAN --}}
<div class="modal fade" id="modalBayarIuran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-cash-coin me-2 text-success"></i>Catat Pembayaran Iuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBayarIuran" method="POST" action="">
                @csrf
                <div class="modal-body py-3">
                    <div class="alert alert-info py-2 px-3 mb-3 rounded-3" style="font-size:0.88rem;">
                        <div>Pembayaran a.n <strong id="modalNamaAnggota">-</strong></div>
                        <div>Periode: <strong id="modalPeriode">-</strong> | Nominal: <strong id="modalNominal">-</strong></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_bayar" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Masuk ke Akun Kas <span class="text-danger">*</span></label>
                        <select name="kas_id" id="modalKasId" class="form-select" required>
                            <option value="">-- Pilih Akun Kas --</option>
                            @foreach($kasList as $kas)
                            <option value="{{ $kas->id }}">{{ $kas->nama }} (Saldo: Rp{{ number_format($kas->saldo, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori Pemasukan <span class="text-danger">*</span></label>
                        <select name="kategori_id" id="modalKategoriId" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriPemasukanList as $kat)
                            <option value="{{ $kat->id }}" {{ str_contains(strtolower($kat->nama), 'iuran') ? 'selected' : '' }}>{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Titip lewat Budi / Cash">
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold">
                        <i class="bi bi-check-lg me-1"></i>Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModalBayar(iuran, namaAnggota) {
    var formAction = "{{ url('/admin/keuangan/iuran') }}/" + iuran.id + "/bayar";
    document.getElementById('formBayarIuran').action = formAction;
    document.getElementById('modalNamaAnggota').innerText = namaAnggota;
    
    var monthNames = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var periodText = monthNames[iuran.bulan] + " " + iuran.tahun;
    document.getElementById('modalPeriode').innerText = periodText;
    document.getElementById('modalNominal').innerText = 'Rp ' + Number(iuran.nominal).toLocaleString('id-ID');

    if (iuran.kas_id) {
        document.getElementById('modalKasId').value = iuran.kas_id;
    }
    if (iuran.kategori_id) {
        document.getElementById('modalKategoriId').value = iuran.kategori_id;
    }

    var modal = new bootstrap.Modal(document.getElementById('modalBayarIuran'));
    modal.show();
}
</script>
@endpush
