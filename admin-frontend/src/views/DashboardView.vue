<template>
  <AdminLayout @logout="logout">
    <div class="row g-3">
      <div class="col-12 col-md-4" v-for="metric in metrics" :key="metric.title">
        <div class="glass p-3 h-100">
          <div class="text-muted">{{ metric.subtitle }}</div>
          <div class="fs-4 fw-bold">{{ metric.title }}</div>
          <div class="small text-success">{{ metric.delta }}</div>
        </div>
      </div>
    </div>
    <div class="row g-3 mt-1">
      <div class="col-12 col-lg-6">
        <div class="glass p-3 h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">أكثر المنتجات مبيعاً</h5>
            <span class="text-muted small">هذا الأسبوع</span>
          </div>
          <ul class="list-group list-group-flush">
            <li v-for="item in demo.stats.topItems" :key="item.name" class="list-group-item bg-transparent text-light d-flex justify-content-between">
              <span>{{ item.name }}</span>
              <span class="badge bg-primary">{{ item.count }}</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="glass p-3 h-100">
          <h5 class="mb-2">إجراءات سريعة</h5>
          <div class="row g-2">
            <div class="col-6 col-md-4" v-for="action in quickActions" :key="action.label">
              <RouterLink class="btn btn-outline-light w-100" :to="action.to">{{ action.label }}</RouterLink>
            </div>
          </div>
          <div class="mt-3 small text-muted">تأكد من تفعيل حالة المتجر وتحديث المنيو قبل بدء اليوم.</div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import AdminLayout from '../components/AdminLayout.vue';
import { useAuthStore } from '../stores/auth';
import { useDemoDataStore } from '../stores/demoData';

const auth = useAuthStore();
const demo = useDemoDataStore();

const metrics = computed(() => [
  { title: `${demo.stats.todaySales} ج.م`, subtitle: 'مبيعات اليوم', delta: '+12% مقابل الأمس' },
  { title: `${demo.stats.weeklyOrders} طلب`, subtitle: 'طلبات الأسبوع', delta: '+7 طلبات' },
  { title: `${demo.stats.avgOrderValue} ج.م`, subtitle: 'متوسط قيمة الطلب', delta: '+4 ج.م' }
]);

const quickActions = [
  { label: 'إضافة منتج', to: '/products' },
  { label: 'إضافة قسم', to: '/categories' },
  { label: 'متابعة الطلبات', to: '/kitchen' },
  { label: 'إعدادات واتساب', to: '/whatsapp' }
];

const logout = async () => {
  await auth.logout();
};
</script>
