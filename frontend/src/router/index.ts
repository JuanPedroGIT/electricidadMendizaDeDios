import { createRouter, createWebHistory } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'
import HomeView from '../views/HomeView.vue'
import LegalView from '../views/LegalView.vue'
import AdminLoginView from '../views/admin/AdminLoginView.vue'
import AdminDashboardView from '../views/admin/AdminDashboardView.vue'
import ServicesView from '../views/admin/ServicesView.vue'
import FaqView from '../views/admin/FaqView.vue'
import SettingsView from '../views/admin/SettingsView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to) {
    if (to.hash) {
      return { el: to.hash, behavior: 'smooth' }
    }
    return { top: 0 }
  },
  routes: [
    // Public routes
    {
      path: '/',
      name: 'home',
      alias: ['/contacto'],
      component: HomeView,
    },
    {
      path: '/aviso-legal',
      name: 'legal',
      component: LegalView,
      props: { title: 'Aviso legal' },
    },
    {
      path: '/privacidad',
      name: 'privacy',
      component: LegalView,
      props: { title: 'Política de privacidad' },
    },
    {
      path: '/politica-cookies',
      name: 'cookies',
      component: LegalView,
      props: { title: 'Política de cookies' },
    },

    // Admin routes
    {
      path: '/admin/login',
      name: 'admin-login',
      component: AdminLoginView,
      meta: { guest: true },
    },
    {
      path: '/admin',
      component: AdminDashboardView,
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          name: 'admin-dashboard',
          component: { template: '<div class="welcome">Selecciona una opción del menú</div>' },
        },
        {
          path: 'services',
          name: 'admin-services',
          component: ServicesView,
        },
        {
          path: 'faq',
          name: 'admin-faq',
          component: FaqView,
        },
        {
          path: 'settings',
          name: 'admin-settings',
          component: SettingsView,
        },
      ],
    },
  ],
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAdminAuthStore()
  authStore.restoreSession()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/admin/login')
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next('/admin')
  } else {
    next()
  }
})

export default router
