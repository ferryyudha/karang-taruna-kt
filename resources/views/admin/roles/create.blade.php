@extends('admin.layouts.app')
@section('title','Tambah Role')
@section('page-title','Tambah Role')
@section('breadcrumb','Admin / Roles / Tambah')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-shield-plus me-2 text-primary"></i>Form Role Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label-admin">Nama Role <span class="text-danger">*</span></label>
                <input type="text" name="name" id="roleNameInput" class="form-control form-control-admin" value="{{ old('name') }}" placeholder="Ketua, Sekretaris, Bendahara..." required>
                @if(count($jabatans) > 0)
                <div class="mt-2">
                    <small class="text-muted d-block mb-1">Rekomendasi dari Jabatan Anggota:</small>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($jabatans as $jab)
                        <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2 rounded-pill suggestion-badge" data-value="{{ $jab }}" style="font-size:0.75rem;">
                            {{ $jab }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="mb-4">
                <label class="form-label-admin">Deskripsi</label>
                <input type="text" name="description" id="roleDescInput" class="form-control form-control-admin" value="{{ old('description') }}" placeholder="Deskripsi singkat role...">
            </div>
            <div class="mb-4">
                <label class="form-label-admin">Hak Akses Menu</label>
                <div class="p-3" style="background:#F8FAFC;border-radius:12px;border:1px solid #E2E8F0;">
                    <div class="row g-2">
                        @foreach($menus as $menu)
                        <div class="col-md-6">
                            <div class="form-check" style="padding:10px 14px;background:white;border-radius:10px;border:1px solid #E2E8F0;">
                                <input class="form-check-input" type="checkbox" name="menus[]"
                                    id="menu_{{ $menu->id }}" value="{{ $menu->id }}"
                                    {{ in_array($menu->id, old('menus', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="menu_{{ $menu->id }}" style="font-size:0.88rem;font-weight:500;">
                                    <i class="bi {{ $menu->icon }} me-2 text-primary"></i>{{ $menu->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-2 d-flex gap-2">
                    <button type="button" onclick="checkAll(true)" class="btn btn-sm btn-light border rounded-3" style="font-size:0.8rem;">Pilih Semua</button>
                    <button type="button" onclick="checkAll(false)" class="btn btn-sm btn-light border rounded-3" style="font-size:0.8rem;">Hapus Pilihan</button>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Simpan Role</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
@push('scripts')
<script>
function checkAll(state) {
    document.querySelectorAll('input[name="menus[]"]').forEach(cb => cb.checked = state);
}

document.querySelectorAll('.suggestion-badge').forEach(btn => {
    btn.addEventListener('click', function() {
        const val = this.getAttribute('data-value');
        document.getElementById('roleNameInput').value = val;
        document.getElementById('roleDescInput').value = 'Akses khusus untuk ' + val;
    });
});
</script>
@endpush
