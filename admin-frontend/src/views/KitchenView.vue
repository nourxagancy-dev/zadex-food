<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <h5 class="mb-0">شاشة المطبخ</h5>
        <small class="text-muted">تحديث تلقائي كل 5 ثواني</small>
      </div>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" v-model="sound" id="sound" />
        <label class="form-check-label" for="sound">تنبيه صوتي</label>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-12 col-md-6" v-for="order in demo.orders" :key="order.id">
        <div class="glass p-3 h-100">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">{{ order.number }}</h5>
            <span class="badge bg-info text-dark">{{ statusLabel(order.status) }}</span>
          </div>
          <div class="text-muted">{{ order.customer }} - {{ order.phone }}</div>
          <div class="text-muted">{{ order.type === 'delivery' ? order.address : 'استلام من الفرع' }}</div>
          <div class="fw-bold mt-2">{{ order.total }} ج.م</div>
          <div class="d-flex gap-2 mt-3 flex-wrap">
            <button class="btn btn-sm btn-outline-light" @click="setStatus(order, 'confirmed')">تأكيد</button>
            <button class="btn btn-sm btn-outline-light" @click="setStatus(order, 'preparing')">تحضير</button>
            <button class="btn btn-sm btn-outline-light" @click="setStatus(order, 'ready')">جاهز</button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import AdminLayout from '../components/AdminLayout.vue';
import { useDemoDataStore } from '../stores/demoData';
import { useAuthStore } from '../stores/auth';

const demo = useDemoDataStore();
const auth = useAuthStore();
const sound = ref(true);
let interval;

const statusLabel = (status) => {
  const map = { pending: 'قيد المراجعة', confirmed: 'تم التأكيد', preparing: 'جار التحضير', ready: 'جاهز' };
  return map[status] || status;
};

const setStatus = (order, status) => {
  demo.updateOrderStatus(order.id, status);
  if (sound.value) {
    const audio = new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg');
    audio.play();
  }
};

onMounted(() => {
  interval = setInterval(() => console.log('polling orders...'), 5000);
});

onUnmounted(() => clearInterval(interval));

const logout = async () => auth.logout();
</script>
