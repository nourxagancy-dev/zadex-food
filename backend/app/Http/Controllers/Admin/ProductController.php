<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OptionGroup;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'optionGroups'])
            ->where('restaurant_id', $request->user()->restaurant_id)
            ->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'option_groups' => 'array'
        ]);

        $data['restaurant_id'] = $request->user()->restaurant_id;
        $product = Product::create($data);
        $this->syncOptionGroups($product, $request->input('option_groups', []));
        return response()->json($product->load('optionGroups'), 201);
    }

    public function show(Product $product)
    {
        return $product->load(['category', 'optionGroups.items']);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'price' => 'numeric',
            'category_id' => 'exists:categories,id',
            'is_active' => 'boolean',
            'option_groups' => 'array'
        ]);
        $product->update($data);
        $this->syncOptionGroups($product, $request->input('option_groups', []));
        return response()->json($product->load('optionGroups'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(status: 204);
    }

    private function syncOptionGroups(Product $product, array $groups)
    {
        $payload = [];
        foreach ($groups as $group) {
            $optionGroup = OptionGroup::find($group['id']);
            if ($optionGroup) {
                $payload[$optionGroup->id] = [
                    'min' => $group['min'] ?? 0,
                    'max' => $group['max'] ?? 0,
                    'is_required' => $group['is_required'] ?? false,
                ];
            }
        }
        if ($payload) {
            $product->optionGroups()->sync($payload);
        }
    }
}
