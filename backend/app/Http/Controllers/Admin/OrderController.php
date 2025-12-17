<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items.options', 'payments'])
            ->where('restaurant_id', $request->user()->restaurant_id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        return $order->load(['items.options', 'payments']);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,on_the_way,completed,cancelled'
        ]);
        $order->update(['status' => $data['status']]);
        return response()->json($order);
    }
}
