<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;


class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->latest()
            ->with(['user', 'paymentProofs', 'items.product'])
            ->get();

        return view('page.admin.orders', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:Menunggu Verifikasi,Diproses,Selesai,Ditolak'],
        ]);

        $newStatus = $validated['status'];

        $order->update([
            'status' => $newStatus,
            // Jika ditolak, pastikan rejection_reason tidak null (optional).
            // Customer bisa upload ulang bukti.
        ]);

        return redirect()->to('/admin/orders')->with('success', 'Status order berhasil diperbarui.');
    }

}



