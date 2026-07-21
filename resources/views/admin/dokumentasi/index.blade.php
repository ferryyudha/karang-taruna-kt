@extends('admin.layouts.app')
@section('title','Dokumentasi')
@section('page-title','Dokumentasi Kegiatan')
@section('breadcrumb','Admin / Dokumentasi')
@section('content')
<div class="row g-4">
    {{-- Upload Form --}}
    <div class="col-lg-4">
        <div class="card-admin">
            <div class="card-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-cloud-upload me-2 text-primary"></i>Upload Foto</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.dokumentasi.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-admin">Pilih Kegiatan <span class="text-danger">*</span></label>
                        <select name="kegiatan_id" class="form-select form-select-admin" required>
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach($kegiatanList as $k)
                                <option value="{{ $k->id }}" {{ (request('kegiatan_id') == $k->id || (isset($selectedKegiatan) && $selectedKegiatan->id == $k->id)) ? 'selected' : '' }}>
                                    {{ $k->nama }} ({{ $k->tanggal->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-admin">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control form-control-admin"
                            placeholder="Keterangan foto (opsional)">
                    </div>
                    <div class="mb-4">
                        <label class="form-label-admin">Foto <span class="text-danger">*</span></label>
                        <div class="upload-area" id="uploadArea" onclick="document.getElementById('fotoInput').click()">
                            <i class="bi bi-cloud-upload-fill" style="font-size:2rem;color:#94A3B8;"></i>
                            <p class="mt-2 mb-0" style="font-size:0.85rem;color:#64748B;">Klik untuk pilih foto/file ZIP</p>
                            <p style="font-size:0.75rem;color:#94A3B8;">JPG, PNG, WebP (Maks 3MB) ATAU File ZIP berisi foto-foto (Maks 20MB)</p>
                            <p id="fileCount" style="font-size:0.8rem;color:#4154F1;display:none;font-weight:600;"></p>
                        </div>
                        <input type="file" name="foto[]" id="fotoInput" multiple accept="image/*,.zip,application/zip,application/x-zip-compressed" style="display:none;" onchange="countFiles(this)">
                    </div>
                    <button type="submit" class="btn-primary-custom w-100">
                        <i class="bi bi-upload me-1"></i>Upload Foto
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Foto Grid --}}
    <div class="col-lg-8">
        @if($selectedKegiatan)
        <div class="mb-3 d-flex align-items-center justify-content-between">
            <div>
                <h6 class="mb-0 fw-bold">{{ $selectedKegiatan->nama }}</h6>
                <small class="text-muted">{{ $dokumentasi->total() }} foto dokumentasi</small>
            </div>
        </div>
        @else
        <div class="mb-3">
            <h6 class="mb-0 fw-bold">Semua Dokumentasi</h6>
            <small class="text-muted">{{ $dokumentasi->total() }} foto</small>
        </div>
        @endif

        <div class="row g-2">
            @forelse($dokumentasi as $doc)
            <div class="col-6 col-md-4">
                <div style="border-radius:12px;overflow:hidden;position:relative;aspect-ratio:4/3;background:#F1F5F9;">
                    <img src="{{ Storage::url($doc->foto) }}" alt="{{ $doc->keterangan }}"
                        style="width:100%;height:100%;object-fit:cover;"
                        onerror="this.src='data:image/svg+xml,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 100 75&quot;><rect fill=&quot;%23f1f5f9&quot; width=&quot;100&quot; height=&quot;75&quot;/></svg>'">
                    <div class="doc-overlay" style="position:absolute;inset:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;opacity:0;transition:0.2s;">
                        <form method="POST" action="{{ route('admin.dokumentasi.destroy', $doc) }}" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:rgba(220,38,38,0.9);color:white;border:none;border-radius:8px;padding:6px 12px;font-size:0.8rem;cursor:pointer;">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                    @if($doc->keterangan)
                    <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,0.7));padding:8px 10px;color:white;font-size:0.75rem;">
                        {{ $doc->keterangan }}
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-images d-block mb-2" style="font-size:3rem;opacity:0.3;"></i>
                Belum ada foto. Upload foto di sini.
            </div>
            @endforelse
        </div>
        @if($dokumentasi->hasPages())
        <div class="mt-3">{{ $dokumentasi->links() }}</div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.upload-area {
    border: 2px dashed #E2E8F0; border-radius: 12px;
    padding: 24px; text-align: center; cursor: pointer;
    transition: all 0.2s;
}
.upload-area:hover { border-color: #4154F1; background: #F8FAFF; }
.col-6 > div:hover .doc-overlay { opacity: 1 !important; }
</style>
@endpush
@push('scripts')
<script>
function countFiles(input) {
    const count = input.files.length;
    const el = document.getElementById('fileCount');
    el.textContent = count + ' foto dipilih';
    el.style.display = 'block';
}
</script>
@endpush
