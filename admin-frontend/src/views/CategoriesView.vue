<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">الأقسام</h5>
        <button class="btn btn-primary" @click="addCategory">قسم جديد</button>
      </div>
    </div>
    <div class="glass p-3">
      <div class="table-responsive">
        <table class="table align-middle text-light">
          <thead>
            <tr>
              <th>الاسم</th>
              <th>الحالة</th>
              <th>عدد المنتجات</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cat in demo.categories" :key="cat.id">
              <td>{{ cat.name }}</td>
              <td><span :class="['badge', cat.status === 'active' ? 'bg-success' : 'bg-secondary']">{{ cat.status === 'active' ? 'نشط' : 'متوقف' }}</span></td>
              <td>{{ cat.products }}</td>
              <td><button class="btn btn-sm btn-outline-light" @click="edit(cat)">تعديل</button></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '../components/AdminLayout.vue';
import { useAuthStore } from '../stores/auth';
import { useDemoDataStore } from '../stores/demoData';

const auth = useAuthStore();
const demo = useDemoDataStore();

const addCategory = () => {
  const name = prompt('اسم القسم');
  if (!name) return;
  demo.upsertCategory({ name, status: 'active', products: 0 });
};

const edit = (cat) => {
  const name = prompt('تعديل الاسم', cat.name) || cat.name;
  demo.upsertCategory({ ...cat, name });
};

const logout = async () => auth.logout();
</script>
