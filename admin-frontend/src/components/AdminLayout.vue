<template>
  <div class="container-fluid py-4">
    <div class="row">
      <aside class="col-12 col-lg-3 col-xl-2 mb-3 mb-lg-0">
        <div class="glass p-3 h-100">
          <div class="d-flex align-items-center mb-4 gap-2">
            <div class="avatar bg-primary text-white">Z</div>
            <div>
              <div class="fw-bold">لوحة التحكم</div>
              <div class="text-muted small">{{ storeStatus }}</div>
            </div>
          </div>
          <nav class="nav flex-column gap-2">
            <RouterLink v-for="link in links" :key="link.to" class="nav-link rounded-3" :to="link.to">
              <i :class="link.icon" class="ms-2"></i>{{ link.label }}
            </RouterLink>
          </nav>
        </div>
      </aside>
      <main class="col-12 col-lg-9 col-xl-10">
        <div class="glass p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
              <div class="small text-muted">{{ today }}</div>
              <h4 class="fw-bold mb-0">مرحباً {{ userName }}</h4>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="badge" :class="isOpen ? 'bg-success' : 'bg-secondary'">
                {{ isOpen ? 'مفتوح' : 'مغلق' }}
              </span>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" :checked="isOpen" @change="toggle" />
                <label class="form-check-label">تبديل الحالة</label>
              </div>
              <button class="btn btn-outline-light btn-sm" @click="$emit('logout')">خروج</button>
            </div>
          </div>
        </div>
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { useDemoDataStore } from '../stores/demoData';

const auth = useAuthStore();
const demo = useDemoDataStore();

const links = [
  { to: '/dashboard', label: 'الرئيسية', icon: 'bi bi-speedometer2' },
  { to: '/categories', label: 'إدارة الأقسام', icon: 'bi bi-grid' },
  { to: '/products', label: 'إدارة المنتجات', icon: 'bi bi-basket' },
  { to: '/orders', label: 'إدارة الطلبات', icon: 'bi bi-receipt' },
  { to: '/kitchen', label: 'شاشة المطبخ', icon: 'bi bi-bell' },
  { to: '/coupons', label: 'العروض والكوبونات', icon: 'bi bi-percent' },
  { to: '/reports', label: 'التقارير', icon: 'bi bi-graph-up' },
  { to: '/settings', label: 'إعدادات المطعم', icon: 'bi bi-gear' },
  { to: '/whatsapp', label: 'إعدادات واتساب', icon: 'bi bi-whatsapp' },
  { to: '/users', label: 'إدارة المستخدمين', icon: 'bi bi-people' },
  { to: '/menu', label: 'عرض المنيو', icon: 'bi bi-box-arrow-up-right' }
];

const userName = computed(() => auth.user?.name || 'المدير العام');
const isOpen = computed(() => demo.open);
const storeStatus = computed(() => (demo.open ? 'المتجر يعمل' : 'المتجر مغلق'));
const today = new Intl.DateTimeFormat('ar-EG', { dateStyle: 'full' }).format(new Date());

const toggle = () => demo.toggleStore(!demo.open);
</script>
