import { ref, computed } from 'vue';
import { defineStore } from 'pinia';

export type ToastType = 'success' | 'error' | 'warning' | 'info';

export interface Toast {
  id: string;
  type: ToastType;
  title: string;
  message: string;
  duration: number;
}

export const useUiStore = defineStore('ui', () => {
  // State
  const isLoading = ref(false);
  const loadingMessage = ref('');
  const globalError = ref<string | null>(null);
  const toasts = ref<Toast[]>([]);

  // Getters
  const hasError = computed(() => !!globalError.value);
  const activeToasts = computed(() => toasts.value);

  // Actions
  function setLoading(loading: boolean, message = ''): void {
    isLoading.value = loading;
    loadingMessage.value = message;
  }

  function setError(error: string | null): void {
    globalError.value = error;
  }

  function clearError(): void {
    globalError.value = null;
  }

  // Toast management
  function addToast(toast: Omit<Toast, 'id'>): string {
    const id = Math.random().toString(36).substring(2, 9);
    const newToast: Toast = { ...toast, id };
    toasts.value.push(newToast);

    if (toast.duration > 0) {
      setTimeout(() => {
        removeToast(id);
      }, toast.duration);
    }

    return id;
  }

  function removeToast(id: string): void {
    const index = toasts.value.findIndex((t) => t.id === id);
    if (index > -1) {
      toasts.value.splice(index, 1);
    }
  }

  function clearAllToasts(): void {
    toasts.value = [];
  }

  // Helper methods
  function success(message: string, title = 'Éxito', duration = 5000): string {
    return addToast({ type: 'success', title, message, duration });
  }

  function error(message: string, title = 'Error', duration = 8000): string {
    return addToast({ type: 'error', title, message, duration });
  }

  function warning(message: string, title = 'Advertencia', duration = 6000): string {
    return addToast({ type: 'warning', title, message, duration });
  }

  function info(message: string, title = 'Información', duration = 5000): string {
    return addToast({ type: 'info', title, message, duration });
  }

  return {
    isLoading,
    loadingMessage,
    globalError,
    toasts,
    hasError,
    activeToasts,
    setLoading,
    setError,
    clearError,
    addToast,
    removeToast,
    clearAllToasts,
    success,
    error,
    warning,
    info,
  };
});
