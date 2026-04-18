<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAdminAuthStore } from '@/stores/adminAuth';
import ToastContainer from '@/components/ToastContainer.vue';

const router = useRouter();
const authStore = useAdminAuthStore();

onMounted(() => {
  authStore.restoreSession();
  if (!authStore.isAuthenticated) {
    router.push('/admin/login');
  }
});

function logout() {
  authStore.logout();
  router.push('/admin/login');
}

const menuItems = [
  { name: 'Servicios', route: '/admin/services', icon: '🏗️', desc: 'Gestionar servicios ofrecidos' },
  { name: 'FAQ', route: '/admin/faq', icon: '❓', desc: 'Preguntas frecuentes' },
  { name: 'Configuración', route: '/admin/settings', icon: '⚙️', desc: 'Ajustes del sitio' },
];
</script>

<template>
  <div class="admin-layout">
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2>Admin</h2>
        <p class="brand">Electricidad Mendoza de Dios</p>
      </div>

      <nav class="sidebar-nav">
        <RouterLink to="/admin" class="nav-item" exact-active-class="active">
          <span class="icon">📊</span>
          <span>Dashboard</span>
        </RouterLink>

        <RouterLink
          v-for="item in menuItems"
          :key="item.route"
          :to="item.route"
          class="nav-item"
          active-class="active"
        >
          <span class="icon">{{ item.icon }}</span>
          <span>{{ item.name }}</span>
        </RouterLink>
      </nav>

      <div class="sidebar-footer">
        <button class="logout-btn" @click="logout">
          <span>🚪</span>
          <span>Cerrar sesión</span>
        </button>
      </div>
    </aside>

    <main class="main-content">
      <header class="top-bar">
        <h1>Panel de administración</h1>
        <div class="user-info">
          <span class="badge">Admin</span>
        </div>
      </header>

      <div class="content">
        <div class="dashboard-grid">
          <div
            v-for="item in menuItems"
            :key="item.route"
            class="dashboard-card"
            @click="$router.push(item.route)"
          >
            <span class="card-icon">{{ item.icon }}</span>
            <h3>{{ item.name }}</h3>
            <p>{{ item.desc }}</p>
          </div>
        </div>

      <div class="info-box">
        <h3>💡 Información</h3>
        <ul>
          <li>La sesión expira automáticamente después de 2 horas de inactividad</li>
          <li>Todos los cambios se guardan directamente en la base de datos</li>
          <li>Se requiere token CSRF para operaciones de modificación</li>
        </ul>
      </div>

      <router-view />
    </div>
  </main>
</div>

<ToastContainer />
</template>

<style scoped>
.welcome {
  padding: 2rem;
  text-align: center;
  color: #888;
  background: #f8f9fa;
  border-radius: 8px;
  margin-top: 1rem;
}

.admin-layout {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 260px;
  background: #1e3a5f;
  color: white;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100vh;
}

.sidebar-header {
  padding: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-header h2 {
  margin: 0;
  font-size: 1.5rem;
}

.brand {
  margin: 0.5rem 0 0;
  font-size: 0.8rem;
  opacity: 0.7;
}

.sidebar-nav {
  flex: 1;
  padding: 1rem 0;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.875rem 1.5rem;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.2s;
}

.nav-item:hover,
.nav-item.active {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

.nav-item .icon {
  font-size: 1.25rem;
  width: 24px;
  text-align: center;
}

.sidebar-footer {
  padding: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.logout-btn {
  width: 100%;
  padding: 0.75rem;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 6px;
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: background 0.2s;
}

.logout-btn:hover {
  background: rgba(255, 255, 255, 0.2);
}

.main-content {
  flex: 1;
  margin-left: 260px;
  background: #f5f7fa;
}

.top-bar {
  background: white;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e0e0e0;
}

.top-bar h1 {
  margin: 0;
  font-size: 1.5rem;
  color: #333;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.badge {
  background: #1e3a5f;
  color: white;
  padding: 0.375rem 0.75rem;
  border-radius: 4px;
  font-size: 0.8rem;
  font-weight: 600;
}

.content {
  padding: 2rem;
}

.dashboard-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.dashboard-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.dashboard-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.card-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  display: block;
}

.dashboard-card h3 {
  margin: 0 0 0.5rem;
  color: #1e3a5f;
}

.dashboard-card p {
  margin: 0;
  color: #666;
  font-size: 0.9rem;
}

.info-box {
  background: #e8f4fd;
  border-left: 4px solid #1e3a5f;
  padding: 1.5rem;
  border-radius: 0 8px 8px 0;
}

.info-box h3 {
  margin: 0 0 1rem;
  color: #1e3a5f;
}

.info-box ul {
  margin: 0;
  padding-left: 1.25rem;
}

.info-box li {
  margin-bottom: 0.5rem;
  color: #444;
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    position: relative;
    height: auto;
  }

  .main-content {
    margin-left: 0;
  }

  .admin-layout {
    flex-direction: column;
  }
}
</style>
