<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">المستخدمون والصلاحيات</h5>
      <button class="btn btn-primary" @click="add">مستخدم جديد</button>
    </div>
    <div class="glass p-3">
      <div class="table-responsive">
        <table class="table text-light align-middle">
          <thead>
            <tr>
              <th>الاسم</th>
              <th>الدور</th>
              <th>البريد</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.email">
              <td>{{ user.name }}</td>
              <td><span class="badge bg-secondary">{{ user.role }}</span></td>
              <td>{{ user.email }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue';
import AdminLayout from '../components/AdminLayout.vue';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();
const users = reactive([
  { name: 'المدير العام', email: 'admin@example.com', role: 'Owner' },
  { name: 'مدير الفرع', email: 'manager@example.com', role: 'Manager' },
  { name: 'كاشير', email: 'cashier@example.com', role: 'Cashier' }
]);

const add = () => {
  const name = prompt('اسم المستخدم');
  const email = prompt('البريد الإلكتروني');
  if (name && email) users.push({ name, email, role: 'Manager' });
};

const logout = async () => auth.logout();
</script>
