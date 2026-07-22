@extends('public.layouts.app')

@section('title', 'Layanan Pengaduan Warga — Karang Taruna')
@section('description', 'Sampaikan pengaduan, laporan fasilitas publik, atau aspirasi warga secara cepat dan transparan.')

@section('content')
{{-- Hero Section --}}
<section class="bg-primary text-white py-5 position-relative overflow-hidden" style="margin-top: 60px; background: linear-gradient(135deg, #1E3A8A, #4154F1, #7C3AED) !important;">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start" data-aos="fade-right">
                <span class="badge-soft badge-soft--info mb-3 fs-6">
                    <i class="bi bi-shield-check me-1"></i>Layanan Resmi Karang Taruna
                </span>
                <h1 class="fw-bold display-5 mb-3">Layanan Pengaduan & Aspirasi Warga</h1>
                <p class="lead text-white-50 mb-4 fs-6">
                    Laporkan permasalahan lingkungan seperti jalan rusak, kebersihan, drainase, atau lampu jalan. Pantau penanganannya secara transparan via Kode Tiket.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="#formLapor" class="btn btn-warning btn-lg rounded-3 fw-bold text-dark px-4 py-2 fs-6">
                        <i class="bi bi-pencil-square me-2"></i>Buat Laporan Baru
                    </a>
                    <a href="#cekTiket" class="btn btn-outline-light btn-lg rounded-3 fw-semibold px-4 py-2 fs-6">
                        <i class="bi bi-search me-2"></i>Cek Status Tiket
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center mt-4 mt-lg-0" data-aos="fade-left">
                <div class="ui-card ui-card--lg p-4 text-dark text-start">
                    <h5 class="fw-bold mb-3"><i class="bi bi-search text-primary me-2"></i>Lacak Status Tiket Anda</h5>
                    <form method="GET" action="{{ route('public.pengaduan') }}#cekTiket">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold">Masukkan Kode Tiket Pengaduan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-ticket-perforated"></i></span>
                                <input type="text" name="tiket" class="form-control text-uppercase font-monospace fw-bold" placeholder="Contoh: LAP-202607-A9K2" value="{{ request('tiket') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 fw-semibold">
                            <i class="bi bi-arrow-right-circle me-1"></i>Cek Status Penanganan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    {{-- Alert Sukses Submit --}}
    @if(session('success_tiket'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 p-4 mb-5 text-dark" style="background:#ECFDF5; border-left: 6px solid #10B981 !important;">
        <div class="d-flex align-items-start gap-3">
            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                <i class="bi bi-check-lg fs-3"></i>
            </div>
            <div>
                <h5 class="fw-bold text-success mb-1">Laporan Anda Berhasil Terkirim!</h5>
                <p class="mb-2 fs-6">
                    Simpan Kode Tiket Pengaduan ini untuk melacak status laporan Anda kapan saja:
                </p>
                <div class="d-inline-flex align-items-center gap-2 bg-white px-3 py-2 rounded-3 border mb-2">
                    <span class="fs-4 fw-bold text-primary font-monospace" id="codeToCopy">{{ session('success_tiket') }}</span>
                    <button class="btn btn-sm btn-outline-secondary rounded-2" onclick="navigator.clipboard.writeText('{{ session('success_tiket') }}'); alert('Kode Tiket disalin!');">
                        <i class="bi bi-copy me-1"></i>Salin
                    </button>
                </div>
                <div class="small text-muted">Pengurus akan segera meninjau dan menindaklanjuti laporan Anda di lapangan.</div>
            </div>
        </div>
    </div>
    @endif

    {{-- Result Section Tracker Tiket --}}
    <div id="cekTiket">
        @if($trackResult)
        <div class="ui-card mb-5 border-primary shadow-sm" data-aos="fade-up">
            <div class="card-header bg-primary text-white p-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-ticket-detailed fs-4"></i>
                    <div>
                        <div class="small text-white-50">KODE TIKET PENGADUAN</div>
                        <h5 class="fw-bold font-monospace mb-0">{{ $trackResult->kode_tiket }}</h5>
                    </div>
                </div>
                <div>
                    @if($trackResult->status === 'diterima')
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-clock me-1"></i>DITERIMA (MENUNGGU)</span>
                    @elseif($trackResult->status === 'diproses')
                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><i class="bi bi-gear-wide-connected me-1"></i>SEDANG DIPROSES</span>
                    @elseif($trackResult->status === 'selesai')
                        <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>SELESAI</span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>DITOLAK</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-7">
                        <span class="badge-soft badge-soft--neutral mb-2">{{ $trackResult->nama_kategori }}</span>
                        <h4 class="fw-bold text-dark mb-2">{{ $trackResult->judul }}</h4>
                        <div class="text-muted small mb-3">
                            <i class="bi bi-person me-1"></i><strong>{{ $trackResult->nama_pelapor }}</strong> &bull; 
                            <i class="bi bi-geo-alt me-1"></i>{{ $trackResult->lokasi }} &bull; 
                            <i class="bi bi-calendar3 me-1"></i>{{ $trackResult->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="p-3 bg-light rounded-3 mb-3" style="white-space: pre-line;">{{ $trackResult->isi_laporan }}</div>

                        @if($trackResult->foto_bukti)
                        <div class="mb-3">
                            <div class="small text-muted fw-semibold mb-1">Foto Bukti Pelapor:</div>
                            <img src="{{ Storage::url($trackResult->foto_bukti) }}" class="img-fluid rounded-3 border" style="max-height: 250px;" alt="Foto Bukti">
                        </div>
                        @endif
                    </div>

                    <div class="col-md-5 border-start-md">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-arrow-repeat text-primary me-2"></i>Status Timeline Penanganan</h6>
                        
                        <div class="position-relative ps-4 ms-2 border-start">
                            {{-- Step 1: Diterima --}}
                            <div class="mb-4 position-relative">
                                <div class="position-absolute top-0 start-0 translate-middle-x bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;left:-1px;">
                                    <i class="bi bi-check fs-6"></i>
                                </div>
                                <div class="fw-bold text-dark">1. Laporan Diterima</div>
                                <div class="small text-muted">{{ $trackResult->created_at->format('d M Y, H:i') }} WIB</div>
                                <div class="small text-secondary">Laporan telah dicatat ke dalam sistem Karang Taruna.</div>
                            </div>

                            {{-- Step 2: Diproses --}}
                            <div class="mb-4 position-relative">
                                <div class="position-absolute top-0 start-0 translate-middle-x {{ in_array($trackResult->status, ['diproses', 'selesai']) ? 'bg-primary text-white' : 'bg-light text-muted border' }} rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;left:-1px;">
                                    <i class="bi bi-gear fs-6"></i>
                                </div>
                                <div class="fw-bold {{ in_array($trackResult->status, ['diproses', 'selesai']) ? 'text-dark' : 'text-muted' }}">2. Penanganan & Proses Lapangan</div>
                                @if(in_array($trackResult->status, ['diproses', 'selesai']))
                                    <div class="small text-muted">Petugas/Pengurus sedang memverifikasi dan berkoordinasi.</div>
                                @else
                                    <div class="small text-muted">Menunggu tindak lanjut pengurus.</div>
                                @endif
                            </div>

                            {{-- Step 3: Selesai / Ditolak --}}
                            <div class="position-relative">
                                <div class="position-absolute top-0 start-0 translate-middle-x {{ $trackResult->status === 'selesai' ? 'bg-success text-white' : ($trackResult->status === 'ditolak' ? 'bg-danger text-white' : 'bg-light text-muted border') }} rounded-circle d-flex align-items-center justify-content-center" style="width:24px;height:24px;left:-1px;">
                                    <i class="bi {{ $trackResult->status === 'selesai' ? 'bi-check-all' : 'bi-flag' }} fs-6"></i>
                                </div>
                                <div class="fw-bold {{ $trackResult->status === 'selesai' ? 'text-success' : ($trackResult->status === 'ditolak' ? 'text-danger' : 'text-muted') }}">
                                    3. Hasil Akhir ({{ ucfirst($trackResult->status) }})
                                </div>

                                @if($trackResult->tanggapan)
                                <div class="alert alert-success border-0 py-2 px-3 mt-2 rounded-3 small">
                                    <div class="fw-bold text-success"><i class="bi bi-chat-left-dots me-1"></i>Tanggapan Pengurus:</div>
                                    <div>{{ $trackResult->tanggapan }}</div>
                                </div>
                                @endif

                                @if($trackResult->foto_penanganan)
                                <div class="mt-2">
                                    <div class="small text-muted fw-semibold mb-1">Foto Bukti Penanganan:</div>
                                    <img src="{{ Storage::url($trackResult->foto_penanganan) }}" class="img-fluid rounded-3 border" style="max-height: 180px;" alt="Foto Penanganan">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif(request('tiket'))
        <div class="alert alert-danger border-0 rounded-4 p-4 mb-5 text-center">
            <i class="bi bi-search fs-1 text-danger d-block mb-2"></i>
            <h5 class="fw-bold">Kode Tiket "{{ request('tiket') }}" Tidak Ditemukan</h5>
            <p class="mb-0 text-muted">Pastikan Anda memasukkan Kode Tiket yang benar yang didapat saat mengirimkan pengaduan.</p>
        </div>
        @endif
    </div>

    {{-- Main Row: Form & Recent Complaints --}}
    <div class="row g-5">
        {{-- FORM KIRIM PENGADUAN --}}
        <div class="col-lg-7" id="formLapor">
            <div class="ui-card">
                <div class="card-header bg-white p-4 border-bottom">
                    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-pencil-square text-primary me-2"></i>Formulir Pengaduan Warga</h4>
                    <p class="text-muted small mb-0">Isi data di bawah ini secara akurat agar pengurus dapat segera menindaklanjuti.</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('public.pengaduan.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Nama Pelapor <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pelapor" class="form-control" placeholder="Nama lengkap Anda..." value="{{ old('nama_pelapor') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">No. WhatsApp / HP <span class="text-danger">*</span></label>
                                <input type="tel" name="phone_pelapor" class="form-control" placeholder="081234567890" value="{{ old('phone_pelapor') }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Kategori Permasalahan <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($daftarKategori as $key => $label)
                                    <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Lokasi Kejadian <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Jl. Mawar RT 02 / RW 01" value="{{ old('lokasi') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Judul Laporan Singkat <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" placeholder="Contoh: Jalan Berlubang Parah Depan Pos Ronda" value="{{ old('judul') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Isi Detail Laporan <span class="text-danger">*</span></label>
                            <textarea name="isi_laporan" class="form-control" rows="4" placeholder="Jelaskan kronologi, kondisi lapangan, atau dampak masalah tersebut secara jelas..." required>{{ old('isi_laporan') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small">Upload Foto Bukti <small class="text-muted">(Opsional, Max 3MB)</small></label>
                            <input type="file" name="foto_bukti" class="form-control" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 fw-bold">
                            <i class="bi bi-send-fill me-2"></i>Kirim Pengaduan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- SIDEBAR: TRANSPARANSI LAPORAN TERBARU --}}
        <div class="col-lg-5">
            <div class="ui-card mb-4">
                <div class="card-header bg-white p-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-stars text-primary me-2"></i>Laporan Warga Terkini</h5>
                    <span class="badge-soft badge-soft--info">{{ $recentPengaduan->count() }} Laporan</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentPengaduan as $p)
                        <div class="list-group-item p-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="badge-soft badge-soft--neutral">{{ $p->nama_kategori }}</span>
                                @if($p->status === 'diterima')
                                    <span class="badge-soft badge-soft--warning"><i class="bi bi-clock me-1"></i>Diterima</span>
                                @elseif($p->status === 'diproses')
                                    <span class="badge-soft badge-soft--info"><i class="bi bi-gear me-1"></i>Diproses</span>
                                @elseif($p->status === 'selesai')
                                    <span class="badge-soft badge-soft--success"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                                @else
                                    <span class="badge-soft badge-soft--danger">Ditolak</span>
                                @endif
                            </div>
                            <h6 class="fw-bold mb-1 text-dark">{{ $p->judul }}</h6>
                            <div class="text-muted small d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($p->lokasi, 25) }}</span>
                                <a href="{{ route('public.pengaduan', ['tiket' => $p->kode_tiket]) }}#cekTiket" class="text-primary fw-semibold text-decoration-none small">
                                    Lihat Status <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h6>Belum ada pengaduan</h6>
                            <p>Belum ada laporan pengaduan masuk saat ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
