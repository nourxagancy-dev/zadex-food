<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3">
      <h5 class="mb-0">التقارير</h5>
      <small class="text-muted">إجمالي المبيعات والمتوسطات</small>
    </div>
    <div class="row g-3">
      <div class="col-12 col-lg-6">
        <div class="glass p-3 h-100">
          <h6>مبيعات اليوم</h6>
          <p class="display-6 fw-bold">{{ demo.stats.todaySales }} ج.م</p>
          <p class="text-muted">مقارنة بالأمس: +12%</p>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="glass p-3 h-100">
          <h6>متوسط قيمة الطلب</h6>
          <p class="display-6 fw-bold">{{ demo.stats.avgOrderValue }} ج.م</p>
          <p class="text-muted">أعلى منتج: {{ demo.stats.topItems[0].name }}</p>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '../components/AdminLayout.vue';
import { useDemoDataStore } from '../stores/demoData';
import { useAuthStore } from '../stores/auth';

const demo = useDemoDataStore();
const auth = useAuthStore();
const logout = async () => auth.logout();
</script>
