@extends('admin.layouts.app')
@section('title','Edit User')
@section('page-title','Edit User')
@section('breadcrumb','Admin / Users / Edit')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card-admin">
    <div class="card-header">
        <h6 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-warning"></i>Edit User — {{ $user->name }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label-admin">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-admin" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-admin" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Password Baru <small class="text-muted">(kosongkan jika tidak ganti)</small></label>
                    <input type="password" name="password" class="form-control form-control-admin" minlength="6">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-admin">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Role <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-select form-select-admin" required>
                        @foreach($roles as $r)
                        <option value="{{ $r->id }}" {{ $user->role_id==$r->id?'selected':'' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">No. HP</label>
                    <input type="text" name="phone" class="form-control form-control-admin" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label-admin">Foto</label>
                    @if($user->foto)<div class="mb-2"><img src="{{ Storage::url($user->foto) }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover;" alt="Foto"></div>@endif
                    <input type="file" name="foto" class="form-control form-control-admin" accept="image/*">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch ms-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_active" style="font-size:0.85rem;">Akun Aktif</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom"><i class="bi bi-check-lg me-1"></i>Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-3">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
