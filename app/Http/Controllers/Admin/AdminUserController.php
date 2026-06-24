<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByRaw("FIELD(role, 'master_admin', 'admin', 'customer')")
            ->orderBy('name')
            ->get();

        return view('page.admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:master_admin,admin,customer'],
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->route('admin.users')
            ->with('success', 'Role pengguna berhasil diperbarui.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('admin.users')
            ->with('success', 'Password berhasil diubah.');
    }
}
