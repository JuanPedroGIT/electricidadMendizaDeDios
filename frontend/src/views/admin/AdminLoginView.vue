<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAdminAuthStore } from '@/stores/adminAuth';

const router = useRouter();
const authStore = useAdminAuthStore();

const password = ref('');
const showPassword = ref(false);

onMounted(() => {
  authStore.restoreSession();
  if (authStore.isAuthenticated) {
    router.push('/admin');
  }
});

async function handleLogin() {
  if (!password.value.trim()) return;

  const success = await authStore.login(password.value);
  if (success) {
    router.push('/admin');
  }
}

function togglePassword() {
  showPassword.value = !showPassword.value;
}
</script>

<template>
  <div class="admin-login">
    <div class="login-card">
      <h1>Acceso Administración</h1>
      <p class="subtitle">Electricidad Mendoza de Dios</p>

      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="password">Contraseña</label>
          <div class="password-input">
            <input
              id="password"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Introduce la contraseña"
              required
              :disabled="authStore.isLoading"
            />
            <button type="button" class="toggle-btn" @click="togglePassword">
              {{ showPassword ? '👁️' : '👁️‍🗨️' }}
            </button>
          </div>
        </div>

        <div v-if="authStore.error" class="error-message">
          {{ authStore.error }}
        </div>

        <button
          type="submit"
          class="login-btn"
          :disabled="authStore.isLoading || !password.trim()"
        >
          <span v-if="authStore.isLoading">Cargando...</span>
          <span v-else>Acceder</span>
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.admin-login {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%);
  padding: 1rem;
}

.login-card {
  background: white;
  border-radius: 12px;
  padding: 2.5rem;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

h1 {
  margin: 0 0 0.5rem;
  color: #1e3a5f;
  font-size: 1.75rem;
  text-align: center;
}

.subtitle {
  color: #666;
  text-align: center;
  margin: 0 0 2rem;
  font-size: 0.95rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #333;
  font-weight: 500;
  font-size: 0.9rem;
}

.password-input {
  position: relative;
}

input {
  width: 100%;
  padding: 0.875rem 2.5rem 0.875rem 1rem;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.2s;
  box-sizing: border-box;
}

input:focus {
  outline: none;
  border-color: #1e3a5f;
}

input:disabled {
  background: #f5f5f5;
  cursor: not-allowed;
}

.toggle-btn {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.25rem;
  padding: 0;
  opacity: 0.6;
  transition: opacity 0.2s;
}

.toggle-btn:hover {
  opacity: 1;
}

.error-message {
  background: #fee;
  color: #c33;
  padding: 0.75rem;
  border-radius: 6px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.login-btn {
  width: 100%;
  padding: 1rem;
  background: #1e3a5f;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}

.login-btn:hover:not(:disabled) {
  background: #2d5a87;
}

.login-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
