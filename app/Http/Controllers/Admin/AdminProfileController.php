<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function index()
    {
        return view('page.admin.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30', 'unique:users,no_hp,' . $user->id],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'no_hp' => $validated['no_hp'],
        ]);

        $user->save();

        return redirect()->route('admin.profile')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
