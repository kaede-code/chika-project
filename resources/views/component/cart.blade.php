@php
    $cartItems = \App\Models\CartItem::with('product')
        ->where('user_id', (int) auth()->id())
        ->get();
    $cartItemCount = $cartItems->sum('qty');
    $cartTotalPrice = $cartItems->sum(fn($item) => $item->qty * ($item->product->harga ?? 0));
@endphp

@if($cartItemCount > 0)
    <a href="{{ route('customer.order') }}" class="cart-float" id="cart-float">
        <div class="cart-summary">
            <div class="cart-items-count" id="cart-count">🛒 {{ $cartItemCount }} Item</div>
            <div class="cart-total-line">Total <span id="cart-total">Rp {{ number_format($cartTotalPrice, 0, ',', '.') }}</span></div>
        </div>
    </a>
@endif
