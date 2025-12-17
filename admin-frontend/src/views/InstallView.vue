<template>
  <div class="container py-5">
    <div class="glass p-4 mb-4">
      <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
          <p class="text-muted mb-1">إعداد سريع يناسب الاستضافة المشتركة</p>
          <h3 class="fw-bold mb-0">شاشة التثبيت</h3>
        </div>
        <span class="badge bg-info text-dark">1) أدخل بيانات قاعدة البيانات 2) انسخ ملف .env</span>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-6">
        <div class="glass p-4 h-100">
          <h5 class="fw-bold mb-3">بيانات الاتصال بقاعدة البيانات</h5>
          <p class="text-muted small mb-4">
            استخدم قيم cPanel / لوحة الاستضافة (Database name, user, host). عادةً يكون DB Host هو
            <code>localhost</code> أو اسم الخادم الظاهر في لوحة التحكم.
          </p>
          <form class="row g-3">
            <div class="col-12">
              <label class="form-label">رابط التطبيق (APP_URL)</label>
              <input v-model="form.appUrl" type="text" class="form-control" placeholder="https://example.com" />
            </div>
            <div class="col-sm-6">
              <label class="form-label">DB Host</label>
              <input v-model="form.dbHost" type="text" class="form-control" placeholder="localhost" />
            </div>
            <div class="col-sm-6">
              <label class="form-label">DB Port</label>
              <input v-model="form.dbPort" type="text" class="form-control" placeholder="3306" />
            </div>
            <div class="col-sm-6">
              <label class="form-label">اسم قاعدة البيانات</label>
              <input v-model="form.dbName" type="text" class="form-control" placeholder="cpanel_dbname" />
            </div>
            <div class="col-sm-6">
              <label class="form-label">مستخدم قاعدة البيانات</label>
              <input v-model="form.dbUser" type="text" class="form-control" placeholder="cpanel_user" />
            </div>
            <div class="col-12">
              <label class="form-label">كلمة المرور</label>
              <input v-model="form.dbPassword" type="password" class="form-control" placeholder="••••••" />
            </div>
            <div class="col-12">
              <label class="form-label">بادئة الجداول (اختياري)</label>
              <input v-model="form.dbPrefix" type="text" class="form-control" placeholder="zf_" />
            </div>
            <div class="col-12">
              <label class="form-label">مسار مجلد Laravel داخل الاستضافة</label>
              <input v-model="form.basePath" type="text" class="form-control" placeholder="/home/user/backend" />
              <div class="form-text text-light">يُستخدم في أوامر التهيئة داخل cPanel أو SSH.</div>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="glass p-4 h-100 d-flex flex-column gap-3">
          <div>
            <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
              <h5 class="fw-bold mb-0">ملف .env جاهز</h5>
              <button class="btn btn-outline-light btn-sm" @click="copyEnv">نسخ</button>
            </div>
            <pre class="bg-dark text-light p-3 rounded" style="white-space: pre-wrap;">{{ envPreview }}</pre>
            <p class="text-muted small mb-0">ضع الملف داخل <code>backend/.env</code> ثم شغّل أوامر التهيئة.</p>
          </div>

          <div>
            <h6 class="fw-bold">خطوات التثبيت على Shared Hosting</h6>
            <ol class="small text-light mb-2">
              <li>أنشئ قاعدة البيانات والمستخدم من cPanel ثم اربطهما مع صلاحية <strong>ALL PRIVILEGES</strong>.</li>
              <li>ارفع مجلد <code>backend</code> إلى خارج <code>public_html</code> (أو في مجلد آمن) وضع مجلدات <code>admin</code>/<code>menu</code> داخل <code>public_html</code>.</li>
              <li>حرّر ملف <code>backend/.env</code> بالقيم أعلاه.</li>
              <li>شغّل الأوامر عبر Terminal داخل cPanel أو SSH:</li>
            </ol>
            <div class="bg-dark text-light p-3 rounded small">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold">أوامر التهيئة</span>
                <button class="btn btn-outline-light btn-sm" @click="copyCommands">نسخ</button>
              </div>
              <code class="d-block">cd {{ form.basePath || '/home/user/backend' }}</code>
              <code class="d-block">php artisan key:generate</code>
              <code class="d-block">php artisan migrate --seed</code>
              <code class="d-block">php artisan storage:link</code>
              <code class="d-block">php artisan config:cache && php artisan route:cache</code>
            </div>
            <p class="text-muted small mb-0">إذا لم يتوفر SSH، يمكنك تشغيل أوامر Artisan عبر <strong>PHP</strong> Selector أو ملف <code>artisan.php</code> مؤقت داخل <code>public_html</code>.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive } from 'vue';

const form = reactive({
  appUrl: 'https://example.com',
  dbHost: 'localhost',
  dbPort: '3306',
  dbName: 'cpanel_dbname',
  dbUser: 'cpanel_user',
  dbPassword: '',
  dbPrefix: '',
  basePath: '/home/user/backend'
});

const envPreview = computed(() => {
  return `APP_NAME=ZadexFood\nAPP_ENV=production\nAPP_KEY=base64:استبدل_بعد_key_generate\nAPP_DEBUG=false\nAPP_URL=${form.appUrl}\n\nLOG_CHANNEL=stack\n\nDB_CONNECTION=mysql\nDB_HOST=${form.dbHost}\nDB_PORT=${form.dbPort}\nDB_DATABASE=${form.dbName}\nDB_USERNAME=${form.dbUser}\nDB_PASSWORD=${form.dbPassword}\nDB_PREFIX=${form.dbPrefix}\n\nFILESYSTEM_DRIVER=public\nSESSION_DRIVER=file\nCACHE_DRIVER=file\nQUEUE_CONNECTION=sync\n\nSANCTUM_STATEFUL_DOMAINS=${form.appUrl}\nFRONTEND_URL=${form.appUrl}`;
});

const copyText = async (text) => {
  if (navigator?.clipboard?.writeText) {
    await navigator.clipboard.writeText(text);
    alert('تم النسخ بنجاح');
  }
};

const copyEnv = () => copyText(envPreview.value);

const copyCommands = () => {
  const commands = [
    `cd ${form.basePath || '/home/user/backend'}`,
    'php artisan key:generate',
    'php artisan migrate --seed',
    'php artisan storage:link',
    'php artisan config:cache && php artisan route:cache'
  ].join('\n');
  copyText(commands);
};
</script>
