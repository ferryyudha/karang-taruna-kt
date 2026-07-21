@extends('admin.layouts.app')
@section('title','Tambah User')
@section('page-title','Tambah User')
@section('breadcrumb','Admin / Users / Tambah')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Form User Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf
            
            {{-- Auto-complete from Anggota --}}
            <div class="mb-4 p-3" style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;">
                <label class="form-label-admin d-block"><i class="bi bi-people me-1 text-primary"></i>Salin Data dari Anggota (Opsional)</label>
                <select id="selectAnggota" class="form-select form-select-admin">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($anggota as $a)
                    <option value="{{ $a->id }}" data-nama="{{ $a->nama }}" data-email="{{ $a->email }}" data-phone="{{ $a->phone }}" data-jabatan="{{ strtolower($a->jabatan) }}">
                        {{ $a->nama }} ({{ $a->jabatan }})
                    </option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-1" style="font-size:0.75rem;">Pilih anggota untuk mengisi Nama, Email, No. HP, dan memilih Role yang sesuai secara otomatis.</small>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label-admin">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-admin" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-admin" value="{{ old('email') }}" required>
                    @error('email')<div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control form-control-admin" required minlength="6">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control form-control-admin" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Role <span class="text-danger">*</span></label>
                    <select name="role_id" id="roleSelect" class="form-select form-select-admin" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $r)
                        <option value="{{ $r->id }}" data-name="{{ strtolower($r->name) }}" {{ old('role_id')==$r->id?'selected':'' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">No. HP</label>
                    <input type="text" name="phone" class="form-control form-control-admin" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Foto</label>
                    <input type="file" name="foto" class="form-control form-control-admin" accept="image/*">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch ms-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                        <label class="form-check-label fw-semibold" for="is_active" style="font-size:0.85rem;">Akun Aktif</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Simpan User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

@push('scripts')
<script>
document.getElementById('selectAnggota').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    if (selected.value) {
        document.querySelector('input[name="name"]').value = selected.getAttribute('data-nama') || '';
        document.querySelector('input[name="email"]').value = selected.getAttribute('data-email') || '';
        document.querySelector('input[name="phone"]').value = selected.getAttribute('data-phone') || '';
        
        // Auto-select role matching jabatan
        const jabatan = selected.getAttribute('data-jabatan');
        const roleSelect = document.getElementById('roleSelect');
        if (roleSelect && jabatan) {
            let matched = false;
            for (let i = 0; i < roleSelect.options.length; i++) {
                const opt = roleSelect.options[i];
                if (opt.getAttribute('data-name') === jabatan) {
                    roleSelect.selectedIndex = i;
                    matched = true;
                    break;
                }
            }
            // Fallback ke 'pengurus' jika tidak ada role spesifik yang cocok
            if (!matched) {
                for (let i = 0; i < roleSelect.options.length; i++) {
                    const opt = roleSelect.options[i];
                    if (opt.getAttribute('data-name') === 'pengurus') {
                        roleSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        }
    }
});
</script>
@endpush
