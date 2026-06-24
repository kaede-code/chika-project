<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerCartController extends Controller
{
    public function add(Request $request, int $productId)
    {
        $userId = (int) Auth::id();

        $product = Product::query()->findOrFail($productId);

        $validated = $request->validate([
            // spec: qty=0 berarti hapus item dari cart
            'qty' => ['required', 'integer', 'min:0'],
        ]);

        $qty = (int) $validated['qty'];

        $cartItem = CartItem::query()->firstOrCreate([
            'user_id' => $userId,
            'product_id' => $product->id,
        ]);

        // qty payload ini adalah qty target (bukan delta). JS mengirim qty=1 saat Pesan,
        // dan mengirim qty yang sudah dihitung untuk (+/-).
        if ($qty === 0) {
            $cartItem->delete();
        } else {
            $cartItem->update([
                'qty' => $qty,
            ]);
        }


        return redirect()->route('customer.menu')->with('success_small', 'Produk berhasil ditambahkan ke pesanan.');
    }
}

