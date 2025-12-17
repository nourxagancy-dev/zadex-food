<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <h5 class="mb-0">الطلبات</h5>
        <small class="text-muted">تغيير حالة الطلب وتفاصيل الدفع</small>
      </div>
      <div class="d-flex gap-2">
        <select v-model="filter" class="form-select bg-transparent text-light" style="width: 180px;">
          <option value="all">كل الحالات</option>
          <option value="pending">قيد المراجعة</option>
          <option value="preparing">جار التحضير</option>
          <option value="ready">جاهز</option>
          <option value="on_the_way">في الطريق</option>
          <option value="completed">مكتمل</option>
          <option value="cancelled">ملغي</option>
        </select>
      </div>
    </div>
    <div class="glass p-3">
      <div class="table-responsive">
        <table class="table text-light align-middle">
          <thead>
            <tr>
              <th>رقم الطلب</th>
              <th>العميل</th>
              <th>نوع الطلب</th>
              <th>المجموع</th>
              <th>الحالة</th>
              <th>تحديث</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in filteredOrders" :key="order.id">
              <td>{{ order.number }}</td>
              <td>{{ order.customer }}<br /><small class="text-muted">{{ order.phone }}</small></td>
              <td>{{ order.type === 'delivery' ? 'توصيل' : 'استلام' }}</td>
              <td>{{ order.total }} ج.م</td>
              <td><span class="badge bg-info text-dark">{{ statusLabel(order.status) }}</span></td>
              <td>
                <select class="form-select form-select-sm bg-transparent text-light" v-model="order.status" @change="update(order)">
                  <option value="pending">قيد المراجعة</option>
                  <option value="confirmed">تم التأكيد</option>
                  <option value="preparing">جار التحضير</option>
                  <option value="ready">جاهز</option>
                  <option value="on_the_way">في الطريق</option>
                  <option value="completed">مكتمل</option>
                  <option value="cancelled">ملغي</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import AdminLayout from '../components/AdminLayout.vue';
import { useDemoDataStore } from '../stores/demoData';
import { useAuthStore } from '../stores/auth';

const demo = useDemoDataStore();
const auth = useAuthStore();
const filter = ref('all');

const filteredOrders = computed(() =>
  filter.value === 'all' ? demo.orders : demo.orders.filter((o) => o.status === filter.value)
);

const statusLabel = (status) => {
  const map = {
    pending: 'قيد المراجعة',
    confirmed: 'تم التأكيد',
    preparing: 'جار التحضير',
    ready: 'جاهز',
    on_the_way: 'في الطريق',
    completed: 'مكتمل',
    cancelled: 'ملغي'
  };
  return map[status] || status;
};

const update = (order) => demo.updateOrderStatus(order.id, order.status);
const logout = async () => auth.logout();
</script>
