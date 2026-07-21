@extends('admin.layouts.app')
@section('title','Edit Role')
@section('page-title','Edit Role')
@section('breadcrumb','Admin / Roles / Edit')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-shield-lock me-2 text-warning"></i>Edit Role — {{ $role->name }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label-admin">Nama Role</label>
                <input type="text" name="name" class="form-control form-control-admin"
                    value="{{ old('name', $role->name) }}" {{ $role->slug === 'admin' ? 'readonly' : '' }}>
                @if($role->slug === 'admin')
                <div class="text-muted mt-1" style="font-size:0.78rem;"><i class="bi bi-info-circle me-1"></i>Nama role Admin tidak dapat diubah.</div>
                @endif
            </div>
            <div class="mb-4">
                <label class="form-label-admin">Deskripsi</label>
                <input type="text" name="description" class="form-control form-control-admin" value="{{ old('description', $role->description) }}">
            </div>
            <div class="mb-4">
                <label class="form-label-admin">Hak Akses Menu</label>
                @if($role->slug === 'admin')
                <div class="p-3" style="background:#FDF4FF;border-radius:12px;border:1px solid #E9D5FF;color:#7C3AED;font-size:0.88rem;">
                    <i class="bi bi-infinity me-2"></i>Role <strong>Admin</strong> memiliki akses ke semua menu secara otomatis.
                </div>
                @else
                <div class="p-3" style="background:#F8FAFC;border-radius:12px;border:1px solid #E2E8F0;">
                    <div class="row g-2">
                        @foreach($menus as $menu)
                        <div class="col-md-6">
                            <div class="form-check" style="padding:10px 14px;background:white;border-radius:10px;border:1px solid {{ in_array($menu->id, $assignedMenuIds) ? '#DBEAFE' : '#E2E8F0' }};">
                                <input class="form-check-input" type="checkbox" name="menus[]"
                                    id="menu_{{ $menu->id }}" value="{{ $menu->id }}"
                                    {{ in_array($menu->id, $assignedMenuIds) ? 'checked' : '' }}>
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
                @endif
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Update Role</button>
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
</script>
@endpush
