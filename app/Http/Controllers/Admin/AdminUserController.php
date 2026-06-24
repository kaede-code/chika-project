<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByRaw("FIELD(role, 'master_admin', 'admin', 'customer')")
            ->orderBy('name')
            ->paginate(10);

        return view('page.admin.users', compact('users'));
    }

    public function edit(User $user)
    {
        return view('page.admin.user-edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30', 'unique:users,no_hp,' . $user->id],
            'role' => ['required', 'string', 'in:master_admin,admin,customer'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
