<template>
  <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="glass p-4 w-100" style="max-width: 440px;">
      <h3 class="mb-3 text-center">تسجيل الدخول</h3>
      <p class="text-muted text-center">دخول آمن إلى لوحة التحكم</p>
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="form-label">البريد الإلكتروني</label>
          <input v-model="form.email" type="email" class="form-control" required placeholder="admin@example.com" />
        </div>
        <div class="mb-3">
          <label class="form-label">كلمة المرور</label>
          <input v-model="form.password" type="password" class="form-control" required placeholder="••••••••" />
        </div>
        <div class="form-check mb-3">
          <input v-model="form.remember" class="form-check-input" type="checkbox" id="remember" />
          <label class="form-check-label" for="remember">تذكرني</label>
        </div>
        <button class="btn btn-primary w-100 py-2" :disabled="auth.loading">
          <span v-if="auth.loading" class="spinner-border spinner-border-sm ms-2"></span>
          دخول
        </button>
        <p v-if="auth.error" class="text-danger mt-2">{{ auth.error }}</p>
      </form>
      <p class="text-center mt-3 text-muted small">
        تحتاج لإعداد قاعدة البيانات على الاستضافة؟
        <RouterLink class="link-light" to="/install">افتح شاشة التثبيت</RouterLink>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const auth = useAuthStore();
const form = reactive({ email: 'admin@example.com', password: 'password', remember: true });

const submit = async () => {
  try {
    await auth.login(form);
    router.push('/dashboard');
  } catch (err) {
    console.error(err.message);
  }
};
</script>

<style scoped>
.glass {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
  backdrop-filter: blur(8px);
}
</style>
