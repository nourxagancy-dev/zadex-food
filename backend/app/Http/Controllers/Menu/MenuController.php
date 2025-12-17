<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function categories(Restaurant $restaurant)
    {
        $categories = Category::where('restaurant_id', $restaurant->id)
            ->where('is_active', true)
            ->get();
        return response()->json($categories);
    }

    public function products(Restaurant $restaurant, Request $request)
    {
        $products = Product::with('optionGroups.items')
            ->where('restaurant_id', $restaurant->id)
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->q, fn($q) => $q->where('name', 'like', "%{$request->q}%"))
            ->where('is_active', true)
            ->get();
        return response()->json($products);
    }

    public function product(Restaurant $restaurant, Product $product)
    {
        abort_unless($product->restaurant_id === $restaurant->id, 404);
        return response()->json($product->load('optionGroups.items'));
    }
}
