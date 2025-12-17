<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantSettingsController extends Controller
{
    public function show(Request $request)
    {
        $restaurant = Restaurant::findOrFail($request->user()->restaurant_id);
        return response()->json($restaurant);
    }

    public function update(Request $request)
    {
        $restaurant = Restaurant::findOrFail($request->user()->restaurant_id);
        $data = $request->validate([
            'name' => 'sometimes|string',
            'logo' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'currency' => 'nullable|string',
            'tax_rate' => 'numeric',
            'delivery_fee' => 'numeric',
            'is_open' => 'boolean'
        ]);
        $restaurant->update($data);
        return response()->json($restaurant);
    }
}
