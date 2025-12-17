<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemOption;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'branch_id' => 'nullable|integer',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'type' => 'required|in:delivery,pickup',
            'address' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.options' => 'array',
            'coupon' => 'nullable|string',
            'payment_method' => 'required|in:cod,paymob,fawry,vodafone_cash',
            'notes' => 'nullable|string'
        ]);

        $restaurant = Restaurant::findOrFail($data['restaurant_id']);
        $number = strtoupper(Str::random(6));

        $subtotal = 0;
        $orderItems = [];
        foreach ($data['items'] as $itemData) {
            $product = Product::with('optionGroups.items')->findOrFail($itemData['product_id']);
            $validatedOptions = $this->validateOptions($product, $itemData['options'] ?? []);
            $lineTotal = $product->price * $itemData['quantity'];
            foreach ($validatedOptions as $opt) {
                $lineTotal += $opt['price'] * $itemData['quantity'];
            }
            $subtotal += $lineTotal;
            $orderItems[] = compact('product', 'validatedOptions', 'lineTotal', 'itemData');
        }

        [$discount, $couponModel] = $this->applyCoupon($data['coupon'] ?? null, $subtotal, $restaurant->id);
        $tax = round($subtotal * ($restaurant->tax_rate ?? 0), 2);
        $delivery = $data['type'] === 'delivery' ? ($restaurant->delivery_fee ?? 0) : 0;
        $total = max(0, $subtotal - $discount + $tax + $delivery);

        $order = Order::create([
            'restaurant_id' => $restaurant->id,
            'branch_id' => $data['branch_id'] ?? null,
            'number' => $number,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'type' => $data['type'],
            'address' => $data['address'] ?? null,
            'payment_method' => $data['payment_method'],
            'payment_status' => $data['payment_method'] === 'cod' ? 'unpaid' : 'pending',
            'status' => 'pending',
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'delivery_fee' => $delivery,
            'total' => $total,
            'notes' => $data['notes'] ?? null,
        ]);

        foreach ($orderItems as $item) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'name' => $item['product']->name,
                'quantity' => $item['itemData']['quantity'],
                'unit_price' => $item['product']->price,
                'total' => $item['lineTotal']
            ]);
            foreach ($item['validatedOptions'] as $opt) {
                OrderItemOption::create([
                    'order_item_id' => $orderItem->id,
                    'option_item_id' => $opt['id'],
                    'name' => $opt['name'],
                    'price' => $opt['price']
                ]);
            }
        }

        if ($couponModel) {
            $couponModel->increment('used_count');
        }

        return response()->json([
            'order' => $order->load('items.options'),
            'whatsapp_message' => $this->whatsappMessage($order)
        ], 201);
    }

    public function show(Order $order)
    {
        return $order->load(['items.options', 'payments']);
    }

    private function validateOptions(Product $product, array $options): array
    {
        $result = [];
        foreach ($product->optionGroups as $group) {
            $selected = collect($options)->where('group_id', $group->id)->first()['items'] ?? [];
            $count = count($selected);
            $min = $group->pivot->min ?? 0;
            $max = $group->pivot->max ?? 0;
            if ($group->pivot->is_required && $count < $min) {
                abort(422, "يجب اختيار {$min} من {$group->name}");
            }
            if ($max && $count > $max) {
                abort(422, "يمكن اختيار {$max} كحد أقصى من {$group->name}");
            }
            foreach ($selected as $itemId) {
                $item = $group->items->firstWhere('id', $itemId);
                if ($item) {
                    $result[] = ['id' => $item->id, 'name' => $item->name, 'price' => $item->price];
                }
            }
        }
        return $result;
    }

    private function applyCoupon(?string $code, float $subtotal, int $restaurantId): array
    {
        if (!$code) {
            return [0, null];
        }
        $coupon = Coupon::where('restaurant_id', $restaurantId)->where('code', $code)->first();
        if (!$coupon || !$coupon->isActive()) {
            abort(422, __('الكوبون غير صالح'));
        }
        if ($coupon->min_subtotal && $subtotal < $coupon->min_subtotal) {
            abort(422, __('قيمة الطلب أقل من الحد الأدنى للكوبون'));
        }
        $discount = $coupon->type === 'percentage' ? ($subtotal * ($coupon->value / 100)) : $coupon->value;
        return [$discount, $coupon];
    }

    private function whatsappMessage(Order $order): string
    {
        $lines = [];
        $lines[] = "رقم الطلب: {$order->number}";
        $lines[] = "العميل: {$order->customer_name} ({$order->customer_phone})";
        $lines[] = "النوع: {$order->type}";
        if ($order->address) {
            $lines[] = "العنوان: {$order->address}";
        }
        foreach ($order->items as $item) {
            $lines[] = "- {$item->name} x{$item->quantity} (" . number_format($item->total, 2) . ")";
            foreach ($item->options as $opt) {
                $lines[] = "   • {$opt->name} +" . number_format($opt->price, 2);
            }
        }
        $lines[] = "المجموع: " . number_format($order->subtotal, 2);
        if ($order->discount) {
            $lines[] = "الخصم: -" . number_format($order->discount, 2);
        }
        $lines[] = "الضريبة: " . number_format($order->tax, 2);
        if ($order->delivery_fee) {
            $lines[] = "توصيل: " . number_format($order->delivery_fee, 2);
        }
        $lines[] = "الإجمالي: " . number_format($order->total, 2);
        $lines[] = "الدفع: {$order->payment_method} ({$order->payment_status})";
        if ($order->notes) {
            $lines[] = "ملاحظات: {$order->notes}";
        }
        return implode("\n", $lines);
    }
}
