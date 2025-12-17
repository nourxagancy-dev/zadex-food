# API v1 Contract (Laravel)

جميع المسارات تبدأ بـ `/api/v1` وتدعم Multi-tenant عبر `restaurant_slug` أو subdomain. الردود تستخدم JSON قياسي مع مفاتيح `data`, `meta`, `errors`.

## المصادقة (Admin)
- `POST /api/v1/admin/login` — الحقول: email, password, remember. Rate limited.
- `POST /api/v1/admin/logout` — يبطل التوكن.
- `GET /api/v1/admin/me` — يرجع بيانات المستخدم + الصلاحيات.

## المنيو (Customer)
- `GET /api/v1/menu/{restaurant_slug}/categories`
- `GET /api/v1/menu/{restaurant_slug}/products?category_id=&q=&sort=`
- `GET /api/v1/menu/{restaurant_slug}/product/{id}`

## السلة/الطلبات (Customer)
- `POST /api/v1/orders`
  - body: restaurant_slug, branch_id?, customer_name, customer_phone, order_type (delivery/pickup), address?, items[], options[], coupon_code?, payment_method, notes.
  - التحقق من قواعد option_groups (min/max/required) قبل الحفظ.
  - ينشئ `order_number` فريد + رسالة واتساب.
- `GET /api/v1/orders/{order_number}` — لاسترجاع حالة الطلب.

## إدارة (Admin CRUD)
- `apiResource /admin/categories`
- `apiResource /admin/products` (يشمل ربط option_groups)
- `apiResource /admin/option-groups`
- `apiResource /admin/coupons`
- `apiResource /admin/users`

## الطلبات (Admin)
- `GET /api/v1/admin/orders?status=&date=`
- `PATCH /api/v1/admin/orders/{id}/status` — ينتج حدث لتحديث شاشة المطبخ.
- `GET /api/v1/admin/kitchen` — polling أو broadcast للأوامر الجديدة مع تنبيه صوتي.

## المدفوعات
- `POST /api/v1/payments/paymob/init` — ينشئ Payment Intent ويرجع redirect/iframe URL.
- `POST /api/v1/payments/paymob/webhook` — يحدّث حالة الطلب عند نجاح/فشل الدفع.
- `POST /api/v1/payments/manual/confirm` — استقبال إثبات دفع لفوري/Vodafone Cash وتغيير الحالة إلى `waiting_payment` أو `paid`.

## الإعدادات
- `GET/PUT /api/v1/admin/settings/restaurant`
- `GET/PUT /api/v1/admin/settings/whatsapp` — رقم الاستقبال + قالب الرسالة.

## رد أمثلة مختصرة
```json
{
  "data": {
    "order_number": "#2024-00123",
    "status": "pending",
    "payment_status": "waiting",
    "whatsapp_link": "https://wa.me/201234567890?text=..."
  }
}
```
