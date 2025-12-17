# نظام المنيو الإلكتروني وإدارة المطعم

هذا المستند يقدّم مخططاً تنفيذياً متكاملاً للنظام المطلوب (Laravel + Vue 3 + React PWA) مع التركيز على الاستضافة على Shared Hosting/cPanel.

## نظرة عامة
- **Multi-tenant اختياري**: يدعم subdomain لكل مطعم أو `restaurant_slug` في المسارات.
- **Backoffice Admin**: Vue 3 + Vite + Bootstrap 5 RTL (تصميم Glassmorphism بنفسجي).
- **Customer Menu**: React + Vite + Bootstrap 5 RTL + PWA مع وضع أوفلاين.
- **API**: Laravel 10+، نسخّة `v1` تحت `/api/v1/...`.
- **الأمان**: Sanctum لجلسات المشرفين، Rate limiting لتسجيل الدخول، صلاحيات Roles/Permissions.

## تدفق الشاشات الأساسية
### لوحة التحكم (Admin)
1. **تسجيل الدخول**: بريد + كلمة مرور + «تذكرني»، معدل محاولات محدود، رسائل عربية.
2. **Dashboard**: بطاقة ترحيب، حالة المطعم، Quick Actions (منتج/قسم/طلبات/واتساب).
3. **الصفحات**: الأقسام، المنتجات، مجموعات الخيارات، الطلبات، شاشة المطبخ (تحديث حي)، العروض والكوبونات، التقارير، إعدادات المطعم/واتساب، إدارة المستخدمين، عرض المنيو، تسجيل خروج.

### واجهة العميل
1. **الرابط/QR**: يفتح `/menu/:restaurant_slug`.
2. **المنيو**: أقسام + بحث + فلترة.
3. **تفاصيل المنتج**: صورة/وصف/سعر + Option Groups مع قواعد min/max/required وأسعار للإضافات.
4. **السلة**: تعديل كميات، حذف، ملخص Subtotal + خصم + ضريبة + توصيل.
5. **Checkout**: بيانات العميل، نوع الطلب (توصيل/استلام مع عنوان/فرع)، ملاحظات، طريقة الدفع (عند الاستلام، Paymob، فوري، Vodafone Cash)، تطبيق كوبون.
6. **إتمام الطلب**: توليد رقم فريد، حفظ العناصر والإضافات، إنشاء رسالة واتساب وفتحها تلقائياً.
7. **Order Success**: رقم الطلب + زر واتساب + زر تتبع الحالة.

## نظرة تقنية (عالية المستوى)
- **Laravel**:
  - Sanctum + Middleware للـ roles (Owner/Manager/Cashier/Kitchen).
  - API Resources: `Category`, `Product`, `OptionGroup`, `Coupon`, `User`, `Order`.
  - Webhooks: Paymob لتأكيد الدفع، Manual confirm لفوري/Vodafone Cash.
  - Jobs/Events: بث تحديثات شاشة المطبخ (Polling/WebSockets)، تنبيه صوتي عند أوامر جديدة.
  - Storage: رفع الصور إلى `storage/app/public` مع `storage:link`.
- **Vue Admin**:
  - تصميم RTL + Glassmorphism (Cairo font) مع Bootstrap 5.
  - مكونات: Toasts، Confirm dialogs، Loading skeletons، شريط جانبي دائم، Quick Action cards.
  - استهلاك API عبر Axios + Interceptors للتوكن.
- **React Customer PWA**:
  - manifest + service worker cache + offline fallback يعرض آخر منيو مخزّن ورسالة عدم الاتصال.
  - Bootstrap 5 RTL + تصميم بنفسجي شفاف.
  - Validation للإضافات (min/max/required) على مستوى Option Group قبل إضافة السلة.

## تجربة المستخدم (UX)
- أزرار كبيرة، ظلال ناعمة، بطاقات شفافة.
- رسائل عربية واضحة للأخطاء والنجاحات.
- يدعم الموبايل بالكامل.

## الأمن والموثوقية
- Rate limit لتسجيل الدخول ومحاولة API حساسة.
- CSRF في لوحة التحكم (جلسات Sanctum) + CORS مضبوط للـ Admin/Customer.
- Logging/Audit للأوامر والتغييرات الحساسة.

## مسارات النشر على Shared Hosting
- Laravel في `/backend` مع public symlink داخل `public_html/backend/public` أو ما يناسب مزود الخدمة.
- بناء Vue Admin داخل `/public/admin`، وبناء React Menu داخل `/public/menu`.
- أوامر النشر: `composer install --no-dev`, `php artisan migrate --seed`, `php artisan storage:link`, `php artisan config:cache route:cache`.

## متطلبات القبول
- RTL كامل، بدون أخطاء Console.
- حفظ الطلبات في DB + فتح واتساب برسالة صحيحة.
- Paymob يعمل مع webhook، وفوري/فودافون كاش بحالة `waiting_payment`.
- صلاحيات المستخدمين، تقارير أساسية، PWA أوفلاين.
