<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::where('restaurant_id', $request->user()->restaurant_id)->get();
        return response()->json($coupons);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric',
            'min_subtotal' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date'
        ]);

        $data['restaurant_id'] = $request->user()->restaurant_id;
        $coupon = Coupon::create($data);
        return response()->json($coupon, 201);
    }

    public function show(Coupon $coupon)
    {
        return $coupon;
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'type' => 'in:percentage,fixed',
            'value' => 'numeric',
            'min_subtotal' => 'numeric',
            'usage_limit' => 'integer|nullable',
            'starts_at' => 'date|nullable',
            'ends_at' => 'date|nullable'
        ]);
        $coupon->update($data);
        return response()->json($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(status: 204);
    }
}
