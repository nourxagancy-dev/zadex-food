# نشر النظام على Shared Hosting / cPanel

## هيكلة المجلدات المقترحة
```
/backend            # Laravel API
/public/admin       # Build لوحة التحكم (Vue 3 + Vite)
/public/menu        # Build واجهة العميل (React + Vite + PWA)
```

## خطوات التحضير (سيرفر البناء المحلي)
1. تثبيت الاعتماديات:
   - Laravel: `composer install` ثم `cp .env.example .env` وضبط بيانات DB.
   - Vue Admin: `npm install && npm run build` مع `BASE=/admin/` إذا لزم.
   - React Menu: `npm install && npm run build` مع `BASE=/menu/` و manifest/Service Worker مفعّلين.
2. نسخ نتائج البناء إلى مجلدات `public/admin` و`public/menu` في الاستضافة.
3. إنشاء symlink لـ Laravel public إلى `public_html/backend/public` أو رفع ملفات public وتوجيه index.php للـ backend.

## إعداد بيئة الإنتاج
- ضبط `.env`:
  - `APP_URL` يشير إلى الدومين الرئيسي.
  - `SANCTUM_STATEFUL_DOMAINS` يضم نطاقات الواجهة الإدارية.
  - إعدادات DB و Paymob (API Key, HMAC, iframe ID, integration ID) وقنوات فوري/Vodafone Cash.
- تشغيل أوامر التحسين:
  - `php artisan migrate --seed`
  - `php artisan storage:link`
  - `php artisan config:cache`
  - `php artisan route:cache`

## CORS و CSRF
- السماح لأصول Vue/React بالوصول إلى `/api/v1`.
- حماية CSRF لجلسات المشرفين (Sanctum) مع إعداد الكوكيز على نفس النطاق الفرعي.

## Cron Jobs (إن توفرت)
- تنظيف التوكنات المنتهية: `php artisan sanctum:prune --hours=24`
- جداول مخصصة لحذف سجلات مؤرشفة أو إرسال تنبيهات.

## المراقبة وحل المشاكل
- تفعيل Logging إلى `storage/logs` + Log rotation إن توفرت صلاحيات.
- التحقق من صلاحيات مجلد `storage` و`bootstrap/cache` (قابلة للكتابة).
- تعطيل Display Errors في الإنتاج وتفعيل إعدادات الأمن في `.htaccess` داخل `backend/public`.
