@extends('admin.layouts.app')
@section('title','Manajemen User')
@section('page-title','Manajemen User')
@section('breadcrumb','Admin / Users')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Daftar User</h5>
        <small class="text-muted">{{ $users->total() }} user terdaftar</small>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-primary-custom">
        <i class="bi bi-person-plus me-1"></i>Tambah User
    </a>
</div>
<div class="card-admin">
    <div class="card-body p-0">
        <table class="table table-admin mb-0">
            <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Status</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @forelse($users as $u)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#4154F1,#7C3AED);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.85rem;overflow:hidden;flex-shrink:0;">
                            @if($u->foto)<img src="{{ Storage::url($u->foto) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                            @else {{ strtoupper(substr($u->name,0,1)) }} @endif
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:0.88rem;">{{ $u->name }}</div>
                            @if($u->id === auth()->id())<span style="font-size:0.72rem;color:#9333EA;">(Anda)</span>@endif
                        </div>
                    </div>
                </td>
                <td style="font-size:0.85rem;color:#64748B;">{{ $u->email }}</td>
                <td>
                    <span style="background:#F0F0FF;color:#4154F1;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;">
                        {{ $u->role->name ?? '-' }}
                    </span>
                </td>
                <td><span class="{{ $u->is_active ? 'badge-publish' : 'badge-draft' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.users.edit', $u) }}" class="btn-edit"><i class="bi bi-pencil me-1"></i>Edit</a>
                        @if($u->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete"><i class="bi bi-trash me-1"></i>Hapus</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada user</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($users->hasPages())<div class="mt-4">{{ $users->links() }}</div>@endif
@endsection
