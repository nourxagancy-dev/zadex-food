<template>
  <AdminLayout @logout="logout">
    <div class="glass p-4 text-center">
      <h5>عرض المنيو</h5>
      <p class="text-muted">افتح واجهة العميل لمراجعة المنيو كما يراها الزبائن.</p>
      <a class="btn btn-primary" href="/menu" target="_blank">فتح المنيو</a>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '../components/AdminLayout.vue';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();
const logout = async () => auth.logout();
</script>
