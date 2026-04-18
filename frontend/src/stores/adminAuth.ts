import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { adminApi } from '@/api/admin';

export const useAdminAuthStore = defineStore('adminAuth', () => {
  // State
  const csrfToken = ref<string | null>(localStorage.getItem('adminCsrfToken'));
  const isLoading = ref(false);
  const error = ref<string | null>(null);

  // Getters
  const isAuthenticated = computed(() => !!csrfToken.value);

  // Actions
  async function login(password: string): Promise<boolean> {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await adminApi.login(password);
      csrfToken.value = response.csrf_token;
      adminApi.setCsrfToken(response.csrf_token);
      localStorage.setItem('adminCsrfToken', response.csrf_token);
      return true;
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Error desconocido';
      return false;
    } finally {
      isLoading.value = false;
    }
  }

  async function logout(): Promise<void> {
    try {
      await adminApi.logout();
    } finally {
      csrfToken.value = null;
      localStorage.removeItem('adminCsrfToken');
    }
  }

  function restoreSession(): void {
    const token = localStorage.getItem('adminCsrfToken');
    if (token) {
      csrfToken.value = token;
      adminApi.setCsrfToken(token);
    }
  }

  function handleSessionExpired(): void {
    csrfToken.value = null;
    localStorage.removeItem('adminCsrfToken');
    error.value = 'Sesión expirada. Por favor, inicia sesión nuevamente.';
  }

  return {
    csrfToken,
    isLoading,
    error,
    isAuthenticated,
    login,
    logout,
    restoreSession,
    handleSessionExpired,
  };
});
