<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('page.login');
    }

    public function showRegister()
    {
        return view('page.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30', 'unique:users,no_hp'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:6'],
        ]);


        $user = User::create([
            'name' => $validated['name'],
            'no_hp' => $validated['no_hp'],
            'password' => bcrypt($validated['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'no_hp' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('no_hp', $validated['no_hp'])->first();

        if (!$user || !password_verify($validated['password'], $user->password)) {
            return back()->withErrors(['no_hp' => 'No HP atau password salah'])->withInput();
        }

        Auth::login($user);

        return redirect('/menu');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

