<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function index()
    {
        return view('page.customer.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30', 'unique:users,no_hp,' . $user->id],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'kabupaten_kota' => ['nullable', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'jalan' => ['nullable', 'string', 'max:500'],
        ]);

        $jalan = $validated['jalan'] ?? '';
        $kecamatan = $validated['kecamatan'] ?? '';
        $kabupatenKota = $validated['kabupaten_kota'] ?? '';
        $provinsi = $validated['provinsi'] ?? '';
        $alamat = implode('||', [$jalan, $kecamatan, $kabupatenKota, $provinsi]);
        if ($alamat === '||||') $alamat = '';

        $user->fill([
            'name' => $validated['name'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $alamat,
        ]);

        $user->save();

        return redirect()->to('/customer/profile')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
