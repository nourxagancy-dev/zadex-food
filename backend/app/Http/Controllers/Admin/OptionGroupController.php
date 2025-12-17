<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OptionGroup;
use Illuminate\Http\Request;

class OptionGroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = OptionGroup::with('items')->where('restaurant_id', $request->user()->restaurant_id)->get();
        return response()->json($groups);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'items' => 'array'
        ]);

        $group = OptionGroup::create([
            'restaurant_id' => $request->user()->restaurant_id,
            'name' => $data['name'],
        ]);

        foreach ($request->input('items', []) as $item) {
            $group->items()->create([
                'name' => $item['name'],
                'price' => $item['price'] ?? 0,
            ]);
        }

        return response()->json($group->load('items'), 201);
    }

    public function show(OptionGroup $optionGroup)
    {
        return $optionGroup->load('items');
    }

    public function update(Request $request, OptionGroup $optionGroup)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'items' => 'array'
        ]);
        $optionGroup->update($data);
        return response()->json($optionGroup->load('items'));
    }

    public function destroy(OptionGroup $optionGroup)
    {
        $optionGroup->delete();
        return response()->json(status: 204);
    }
}
