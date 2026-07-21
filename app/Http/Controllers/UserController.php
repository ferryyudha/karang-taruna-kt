<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        $anggota = \App\Models\Anggota::where('aktif', true)->orderBy('nama')->get();
        return view('admin.users.create', compact('roles', 'anggota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role_id'  => 'required|exists:roles,id',
            'phone'    => 'nullable|string|max:20',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
            'is_active'=> 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = ImageUploadService::uploadThumbnail($request->file('foto'), 'users');
        }

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role_id'  => 'required|exists:roles,id',
            'phone'    => 'nullable|string|max:20',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=6000,max_height=6000',
            'is_active'=> 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $validated['foto'] = ImageUploadService::uploadThumbnail($request->file('foto'), 'users');
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        try {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            // User ini masih punya riwayat Pengumuman/Kegiatan/Keuangan (dicegah oleh restrictOnDelete)
            return redirect()->route('admin.users.index')
                ->with('error', 'User ini masih memiliki riwayat data (pengumuman/kegiatan/keuangan) sehingga tidak bisa dihapus. Nonaktifkan akunnya saja lewat tombol Edit.');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }
}
