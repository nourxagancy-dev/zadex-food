<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\OptionGroup;
use App\Models\OptionItem;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::create([
            'name' => 'Zadex Demo',
            'slug' => 'zadex-demo',
            'currency' => 'EGP',
            'tax_rate' => 0.14,
            'delivery_fee' => 25,
            'whatsapp_number' => '+201000000000'
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'restaurant_id' => $restaurant->id,
        ]);
        $admin->assignRole('Owner');

        $burgers = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'برجر',
            'description' => 'أشهى البرجر',
        ]);

        $sauces = OptionGroup::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'الصوص'
        ]);
        foreach ([['name' => 'ثوم', 'price' => 2], ['name' => 'سبايسي', 'price' => 3]] as $item) {
            OptionItem::create($item + ['option_group_id' => $sauces->id]);
        }

        $burger = Product::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $burgers->id,
            'name' => 'برجر دبل',
            'description' => 'لحم طازج مع جبن',
            'price' => 120,
        ]);
        $burger->optionGroups()->attach($sauces->id, ['min' => 0, 'max' => 2, 'is_required' => false]);
    }
}
