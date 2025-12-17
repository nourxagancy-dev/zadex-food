<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">الكوبونات والعروض</h5>
      <button class="btn btn-primary" @click="addCoupon">كوبون جديد</button>
    </div>
    <div class="glass p-3">
      <div class="table-responsive">
        <table class="table text-light align-middle">
          <thead>
            <tr>
              <th>الكود</th>
              <th>النوع</th>
              <th>القيمة</th>
              <th>حد أدنى</th>
              <th>الصلاحية</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="coupon in demo.coupons" :key="coupon.id">
              <td>{{ coupon.code }}</td>
              <td>{{ coupon.type === 'percentage' ? 'نسبة مئوية' : 'قيمة ثابتة' }}</td>
              <td>{{ coupon.value }}</td>
              <td>{{ coupon.min }}</td>
              <td>{{ coupon.expires_at }}</td>
            </tr>
          </tbody>
        </table>
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

const addCoupon = () => {
  const code = prompt('كود الكوبون');
  if (!code) return;
  demo.coupons.push({ id: Date.now(), code, type: 'percentage', value: 10, min: 50, expires_at: '2024-12-31' });
};

const logout = async () => auth.logout();
</script>
