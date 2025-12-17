<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3">
      <h5 class="mb-0">إعدادات واتساب</h5>
      <small class="text-muted">قالب الرسالة + الرقم</small>
    </div>
    <div class="glass p-3">
      <form class="row g-3">
        <div class="col-12">
          <label class="form-label">رقم الاستقبال</label>
          <input v-model="demo.whatsappNumber" class="form-control" />
        </div>
        <div class="col-12">
          <label class="form-label">قالب الرسالة</label>
          <textarea v-model="demo.whatsappTemplate" class="form-control" rows="4"></textarea>
          <small class="text-muted">المتغيرات المتاحة: {{number}} {{name}} {{phone}} {{total}} {{currency}}</small>
        </div>
        <div class="col-12 d-flex justify-content-end">
          <button class="btn btn-primary" type="button" @click="save">حفظ</button>
        </div>
        <div v-if="saved" class="alert alert-success">تم حفظ الإعدادات</div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import AdminLayout from '../components/AdminLayout.vue';
import { useDemoDataStore } from '../stores/demoData';
import { useAuthStore } from '../stores/auth';

const demo = useDemoDataStore();
const auth = useAuthStore();
const saved = ref(false);

const save = () => {
  saved.value = true;
  setTimeout(() => (saved.value = false), 1500);
};

const logout = async () => auth.logout();
</script>
