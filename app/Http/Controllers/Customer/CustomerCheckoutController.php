<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerCheckoutController extends Controller
{
    public function confirm(Request $request)
    {
        $userId = (int) Auth::id();
        $user = Auth::user();

        $alamatUser = trim((string) ($user->alamat ?? ''));
        if ($alamatUser === '') {
            return redirect()->back()->withErrors([
                'alamat' => 'Alamat pengiriman belum diisi. Silakan lengkapi profil terlebih dahulu.',
            ]);
        }

        $noHpUser = trim((string) ($user->no_hp ?? ''));
        if ($noHpUser === '') {
            return redirect()->back()->withErrors([
                'no_hp' => 'Nomor HP belum tersedia. Silakan lengkapi data pada menu Pusat Akun terlebih dahulu.',
            ]);
        }

        $validated = $request->validate([
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'bukti.mimes' => 'File harus berupa jpg, jpeg, png, atau pdf.',
            'bukti.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $cartItems = CartItem::query()
            ->where('user_id', $userId)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.menu')->withErrors([
                'cart' => 'Keranjang masih kosong. Silakan pilih produk terlebih dahulu.',
            ]);
        }

        $reference = 'INV-' . Str::upper(Str::random(8));

        DB::beginTransaction();
        try {
            $totalAmount = 0;

            $order = Order::query()->create([
                'user_id' => $userId,
                'status' => 'Menunggu Verifikasi',
                'total_amount' => 0,
                'shipping_address' => $alamatUser,
                'alamat' => $alamatUser,
                'recipient_no_hp' => $noHpUser,
                'recipient_name' => $user->name,
                'reference' => $reference,
            ]);

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                if (!$product) continue;

                $qty = (int) $cartItem->qty;
                $unitPrice = (int) $product->harga;
                $subtotal = $unitPrice * $qty;
                $totalAmount += $subtotal;

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $totalAmount]);

            $file = $validated['bukti'];
            $storedPath = $file->store('payment-proofs', 'public');

            PaymentProof::create([
                'order_id' => $order->id,
                'file_path' => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
            ]);

            CartItem::query()->where('user_id', $userId)->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->to('/customer/riwayat')->with(
            'success',
            'Pesanan berhasil dikonfirmasi. Status: Menunggu Verifikasi.'
        );
    }
}
