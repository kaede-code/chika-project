<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentProofController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $userId = (int) \Illuminate\Support\Facades\Auth::id();
        abort_unless((int) $order->user_id === $userId, 403);

        $validated = $request->validate([
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'bukti.mimes' => 'File harus berupa jpg, jpeg, png, atau pdf.',
            'bukti.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $file = $validated['bukti'];

        $storedPath = $file->store('payment-proofs', 'public');

        PaymentProof::create([
            'order_id' => $order->id,
            'file_path' => $storedPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
        ]);

        // alamat wajib sebelum upload bukti (gunakan shipping_address yang ada di DB)
        if (!$order->shipping_address || trim((string) $order->shipping_address) === '') {
            return redirect()->to('/customer/order')->withErrors([
                'alamat' => 'Alamat pengiriman belum diisi. Silakan lengkapi profil terlebih dahulu.',
            ]);
        }

        $order->update([
            'status' => 'Menunggu Verifikasi',
        ]);





        return redirect()->to('/customer/riwayat')->with('success', 'Bukti pembayaran berhasil dikirim. Status order: Menunggu Verifikasi.');
    }
}

