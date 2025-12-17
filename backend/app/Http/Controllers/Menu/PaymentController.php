<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function initPaymob(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric'
        ]);

        $payment = Payment::create([
            'order_id' => $data['order_id'],
            'method' => 'paymob',
            'status' => 'pending',
            'amount' => $data['amount'],
            'reference' => uniqid('paymob_'),
        ]);

        return response()->json([
            'redirect_url' => 'https://accept.paymob.com/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token={token}',
            'payment' => $payment,
        ]);
    }

    public function handlePaymobWebhook(Request $request)
    {
        $orderId = $request->input('order');
        $success = $request->input('success');
        $order = Order::findOrFail($orderId);
        $order->update(['payment_status' => $success ? 'paid' : 'failed']);
        return response()->json(['status' => 'ok']);
    }

    public function manualConfirm(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|in:fawry,vodafone_cash',
            'reference' => 'nullable|string'
        ]);
        $order = Order::findOrFail($data['order_id']);
        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => $data['method'],
            'status' => 'waiting_payment',
            'amount' => $order->total,
            'reference' => $data['reference'] ?? null,
        ]);
        return response()->json($payment, 201);
    }
}
