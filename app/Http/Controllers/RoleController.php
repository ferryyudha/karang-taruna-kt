<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $menus = Menu::where('is_active', true)->orderBy('order')->get();
        $jabatans = \App\Models\Anggota::distinct()->pluck('jabatan')->filter()->toArray();
        return view('admin.roles.create', compact('menus', 'jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'menus'       => 'nullable|array',
            'menus.*'     => 'exists:menus,id',
        ]);

        // Prevent creating another admin role
        if (strtolower($validated['name']) === 'admin') {
            return back()->with('error', 'Role "admin" sudah ada dan tidak bisa dibuat lagi.');
        }

        $role = Role::create([
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        if (!empty($validated['menus'])) {
            $role->menus()->sync($validated['menus']);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' berhasil dibuat!");
    }

    public function edit(Role $role)
    {
        $menus = Menu::where('is_active', true)->orderBy('order')->get();
        $assignedMenuIds = $role->menus->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'menus', 'assignedMenuIds'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'menus'       => 'nullable|array',
            'menus.*'     => 'exists:menus,id',
        ]);

        // Protect admin role slug
        if ($role->slug !== 'admin') {
            $role->update([
                'name'        => $validated['name'],
                'slug'        => Str::slug($validated['name']),
                'description' => $validated['description'] ?? null,
            ]);
        }

        $role->menus()->sync($validated['menus'] ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->name}' berhasil diperbarui!");
    }

    public function destroy(Role $role)
    {
        if ($role->slug === 'admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role admin tidak bisa dihapus!');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', "Role ini masih digunakan oleh {$role->users()->count()} user!");
        }

        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus!');
    }
}
