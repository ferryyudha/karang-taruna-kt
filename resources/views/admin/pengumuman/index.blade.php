@extends('admin.layouts.app')
@section('title','Pengumuman')
@section('page-title','Pengumuman')
@section('breadcrumb','Admin / Pengumuman')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Daftar Pengumuman</h5>
        <small class="text-muted">{{ $pengumuman->total() }} pengumuman terdaftar</small>
    </div>
    <a href="{{ route('admin.pengumuman.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-lg me-1"></i>Tambah Pengumuman
    </a>
</div>
<div class="card-admin">
    <div class="card-body p-0">
        <table class="table table-admin mb-0">
            <thead>
                <tr><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th>Dibuat Oleh</th><th class="text-center">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($pengumuman as $p)
            <tr>
                <td style="max-width:220px;font-weight:500;">{{ Str::limit($p->judul, 40) }}</td>
                <td>{{ $p->kategori ?? '-' }}</td>
                <td>{{ $p->tanggal->format('d M Y') }}</td>
                <td><span class="badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                <td>{{ $p->user->name ?? '-' }}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.pengumuman.edit', $p) }}" class="btn-edit"><i class="bi bi-pencil me-1"></i>Edit</a>
                        <form method="POST" action="{{ route('admin.pengumuman.destroy', $p) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete"><i class="bi bi-trash me-1"></i>Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-megaphone d-block mb-2" style="font-size:2rem;opacity:0.3;"></i>Belum ada pengumuman</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($pengumuman->hasPages())
<div class="mt-4">{{ $pengumuman->links() }}</div>
@endif
@endsection
