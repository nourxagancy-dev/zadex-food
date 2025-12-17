import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/LoginView.vue';
import DashboardView from '../views/DashboardView.vue';
import CategoriesView from '../views/CategoriesView.vue';
import ProductsView from '../views/ProductsView.vue';
import OrdersView from '../views/OrdersView.vue';
import KitchenView from '../views/KitchenView.vue';
import CouponsView from '../views/CouponsView.vue';
import ReportsView from '../views/ReportsView.vue';
import SettingsView from '../views/SettingsView.vue';
import WhatsappView from '../views/WhatsappView.vue';
import UsersView from '../views/UsersView.vue';
import MenuLinkView from '../views/MenuLinkView.vue';
import InstallView from '../views/InstallView.vue';
import { useAuthStore } from '../stores/auth';

const routes = [
  { path: '/', redirect: '/dashboard' },
  { path: '/login', name: 'login', component: LoginView, meta: { guest: true } },
  { path: '/install', name: 'install', component: InstallView },
  { path: '/dashboard', name: 'dashboard', component: DashboardView, meta: { requiresAuth: true } },
  { path: '/categories', name: 'categories', component: CategoriesView, meta: { requiresAuth: true } },
  { path: '/products', name: 'products', component: ProductsView, meta: { requiresAuth: true } },
  { path: '/orders', name: 'orders', component: OrdersView, meta: { requiresAuth: true } },
  { path: '/kitchen', name: 'kitchen', component: KitchenView, meta: { requiresAuth: true } },
  { path: '/coupons', name: 'coupons', component: CouponsView, meta: { requiresAuth: true } },
  { path: '/reports', name: 'reports', component: ReportsView, meta: { requiresAuth: true } },
  { path: '/settings', name: 'settings', component: SettingsView, meta: { requiresAuth: true } },
  { path: '/whatsapp', name: 'whatsapp', component: WhatsappView, meta: { requiresAuth: true } },
  { path: '/users', name: 'users', component: UsersView, meta: { requiresAuth: true } },
  { path: '/menu', name: 'menu', component: MenuLinkView, meta: { requiresAuth: true } }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();
  if (to.meta.requiresAuth && !auth.isAuthenticated) return next({ name: 'login' });
  if (to.meta.guest && auth.isAuthenticated) return next({ name: 'dashboard' });
  next();
});

export default router;
