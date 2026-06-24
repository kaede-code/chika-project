<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CustomerMenuController extends Controller
{
    public function index()
    {
        $products = Product::query()->latest()->get();

        // indikator qty di menu berdasarkan cart_items user
        $userId = (int) Auth::id();
        $cartQuantities = CartItem::query()
            ->where('user_id', $userId)
            ->pluck('qty', 'product_id');

        return view('page.customer.menu', compact('products', 'cartQuantities'));
    }
}


