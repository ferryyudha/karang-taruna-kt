@extends('admin.layouts.app')
@section('title','Role & Akses')
@section('page-title','Role & Akses Menu')
@section('breadcrumb','Admin / Roles')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Daftar Role</h5>
        <small class="text-muted">Kelola role dan hak akses menu</small>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i>Tambah Role
    </a>
</div>
<div class="row g-3">
@foreach($roles as $role)
<div class="col-md-6 col-lg-4">
    <div class="card-admin" style="padding:20px;">
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div>
                <h6 class="fw-bold mb-1">{{ $role->name }}</h6>
                <span style="font-size:0.75rem;background:#F1F5F9;color:#64748B;padding:3px 10px;border-radius:20px;font-family:monospace;">{{ $role->slug }}</span>
            </div>
            <span style="background:linear-gradient(135deg,#EFF6FF,#DBEAFE);color:#1D4ED8;padding:4px 12px;border-radius:20px;font-size:0.78rem;font-weight:600;">
                {{ $role->users_count }} user
            </span>
        </div>
        @if($role->description)
        <p style="font-size:0.82rem;color:#64748B;margin-bottom:12px;">{{ $role->description }}</p>
        @endif
        <div class="mb-3">
            <div style="font-size:0.75rem;color:#94A3B8;margin-bottom:6px;font-weight:600;">AKSES MENU:</div>
            <div class="d-flex flex-wrap gap-1">
                @forelse($role->menus as $menu)
                <span style="background:#F0FDF4;color:#15803D;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                    <i class="bi {{ $menu->icon }} me-1" style="font-size:0.65rem;"></i>{{ $menu->name }}
                </span>
                @empty
                <span style="color:#94A3B8;font-size:0.78rem;">Belum ada akses</span>
                @endforelse
                @if($role->slug === 'admin')
                <span style="background:#FDF4FF;color:#9333EA;padding:3px 8px;border-radius:6px;font-size:0.72rem;font-weight:600;">
                    <i class="bi bi-infinity me-1"></i>Semua Menu
                </span>
                @endif
            </div>
        </div>
        <div class="d-flex gap-2 mt-2">
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn-edit flex-fill text-center">
                <i class="bi bi-pencil me-1"></i>Edit Akses
            </a>
            @if($role->slug !== 'admin')
            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Hapus role {{ $role->name }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
            </form>
            @endif
        </div>
    </div>
</div>
@endforeach
</div>
@endsection
