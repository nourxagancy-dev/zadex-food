import { defineStore } from 'pinia';

const now = new Date();
const sampleOptions = [{ id: 1, name: 'زيادة جبنة', price: 7 }, { id: 2, name: 'صوص ثوم', price: 3 }];

export const useDemoDataStore = defineStore('demoData', {
  state: () => ({
    open: true,
    categories: [
      { id: 1, name: 'برجر', status: 'active', products: 3 },
      { id: 2, name: 'بيتزا', status: 'active', products: 5 },
      { id: 3, name: 'مشروبات', status: 'inactive', products: 4 }
    ],
    products: [
      { id: 101, name: 'برجر كلاسيك', category_id: 1, price: 75, sku: 'BR-01', options: sampleOptions },
      { id: 102, name: 'برجر دوبل', category_id: 1, price: 95, sku: 'BR-02', options: sampleOptions },
      { id: 201, name: 'بيتزا مارجريتا', category_id: 2, price: 120, sku: 'PZ-01', options: sampleOptions }
    ],
    orders: [
      {
        id: 9001,
        number: 'Z-9001',
        customer: 'أحمد',
        phone: '01000000000',
        type: 'delivery',
        address: 'التجمع الخامس - فيلا 12',
        status: 'pending',
        total: 230,
        created_at: now.toISOString()
      },
      {
        id: 9002,
        number: 'Z-9002',
        customer: 'سارة',
        phone: '01200000000',
        type: 'pickup',
        address: 'فرع الرحاب',
        status: 'preparing',
        total: 150,
        created_at: now.toISOString()
      }
    ],
    coupons: [
      { id: 1, code: 'WELCOME10', type: 'percentage', value: 10, min: 100, expires_at: '2024-12-31' }
    ],
    stats: {
      todaySales: 1520,
      weeklyOrders: 87,
      avgOrderValue: 174,
      topItems: [
        { name: 'برجر دوبل', count: 22 },
        { name: 'بيتزا مارجريتا', count: 18 },
        { name: 'تشيز فرايز', count: 15 }
      ]
    },
    whatsappTemplate:
      'رقم الطلب: {{number}}\nالاسم: {{name}}\nالهاتف: {{phone}}\nالاجمالي: {{total}} {{currency}}',
    whatsappNumber: '+201234567890'
  }),
  actions: {
    toggleStore(isOpen) {
      this.open = isOpen;
    },
    upsertCategory(payload) {
      const idx = this.categories.findIndex((c) => c.id === payload.id);
      if (idx >= 0) this.categories[idx] = payload;
      else this.categories.push({ ...payload, id: Date.now() });
    },
    upsertProduct(payload) {
      const idx = this.products.findIndex((p) => p.id === payload.id);
      if (idx >= 0) this.products[idx] = payload;
      else this.products.push({ ...payload, id: Date.now() });
    },
    updateOrderStatus(id, status) {
      const order = this.orders.find((o) => o.id === id);
      if (order) order.status = status;
    }
  }
});
