<template>
  <AdminLayout @logout="logout">
    <div class="glass p-3 mb-3">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <h5 class="mb-0">المنتجات</h5>
          <small class="text-muted">مع إدارة المجموعات الاختيارية لكل منتج</small>
        </div>
        <button class="btn btn-primary" @click="addProduct">منتج جديد</button>
      </div>
    </div>
    <div class="glass p-3">
      <div class="table-responsive">
        <table class="table align-middle text-light">
          <thead>
            <tr>
              <th>الاسم</th>
              <th>القسم</th>
              <th>السعر</th>
              <th>SKU</th>
              <th>الاختيارات</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in demo.products" :key="product.id">
              <td>{{ product.name }}</td>
              <td>{{ categoryName(product.category_id) }}</td>
              <td>{{ product.price }} ج.م</td>
              <td>{{ product.sku }}</td>
              <td>
                <ul class="list-inline mb-0">
                  <li v-for="opt in product.options" :key="opt.id" class="list-inline-item badge bg-secondary me-1">{{ opt.name }} (+{{ opt.price }})</li>
                </ul>
              </td>
              <td>
                <button class="btn btn-sm btn-outline-light" @click="edit(product)">تعديل</button>
              </td>
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

const categoryName = (id) => demo.categories.find((c) => c.id === id)?.name || '-';

const addProduct = () => {
  const name = prompt('اسم المنتج');
  if (!name) return;
  demo.upsertProduct({ name, price: 50, category_id: demo.categories[0]?.id, sku: 'NEW-1', options: [] });
};

const edit = (product) => {
  const name = prompt('تعديل الاسم', product.name) || product.name;
  demo.upsertProduct({ ...product, name });
};

const logout = async () => auth.logout();
</script>
