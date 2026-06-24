<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index()
    {
        $this->autoAcceptExpired();

        $orders = Order::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->with(['paymentProofs', 'items.product'])
            ->get();

        return view('page.customer.riwayat', compact('orders'));
    }

    public function terima(Request $request, Order $order)
    {
        $userId = (int) Auth::id();
        abort_unless((int) $order->user_id === $userId, 403);

        if ($order->status !== 'Diproses') {
            return redirect()->back()->withErrors(['status' => 'Status order tidak valid.']);
        }

        $order->update(['status' => 'Selesai']);

        return redirect()->route('customer.riwayat')
            ->with('success', 'Pesanan telah diterima. Terima kasih!');
    }

    private function autoAcceptExpired(): void
    {
        $cutoff = Carbon::now()->subDays(3);

        Order::query()
            ->where('user_id', Auth::id())
            ->where('status', 'Diproses')
            ->where('updated_at', '<=', $cutoff)
            ->update(['status' => 'Selesai']);
    }
}
