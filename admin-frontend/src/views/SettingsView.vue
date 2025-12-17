<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3">
      <h5 class="mb-0">إعدادات المطعم</h5>
      <small class="text-muted">اسم المطعم، العملة، الضريبة، أوقات العمل</small>
    </div>
    <div class="glass p-3">
      <form class="row g-3">
        <div class="col-md-6">
          <label class="form-label">اسم المطعم</label>
          <input v-model="settings.name" class="form-control" placeholder="مطعم زادكس" />
        </div>
        <div class="col-md-3">
          <label class="form-label">العملة</label>
          <input v-model="settings.currency" class="form-control" />
        </div>
        <div class="col-md-3">
          <label class="form-label">الضريبة %</label>
          <input v-model.number="settings.vat" class="form-control" type="number" />
        </div>
        <div class="col-md-6">
          <label class="form-label">رقم واتساب</label>
          <input v-model="settings.whatsapp" class="form-control" />
        </div>
        <div class="col-md-6">
          <label class="form-label">رسوم التوصيل</label>
          <input v-model.number="settings.delivery_fee" type="number" class="form-control" />
        </div>
        <div class="col-12 d-flex justify-content-end gap-2">
          <button class="btn btn-outline-light" type="reset">إلغاء</button>
          <button class="btn btn-primary" type="button" @click="save">حفظ</button>
        </div>
        <div v-if="saved" class="alert alert-success mt-2">تم حفظ الإعدادات</div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import AdminLayout from '../components/AdminLayout.vue';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();
const saved = ref(false);
const settings = reactive({ name: 'مطعم زادكس', currency: 'EGP', vat: 14, delivery_fee: 20, whatsapp: '+201234567890' });

const save = () => {
  saved.value = true;
  setTimeout(() => (saved.value = false), 1500);
};

const logout = async () => auth.logout();
</script>
