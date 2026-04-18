import { ref } from 'vue';
import { defineStore } from 'pinia';

export type NotificationType = 'success' | 'error' | 'warning' | 'info';

export interface Notification {
  id: string;
  type: NotificationType;
  title: string;
  message: string;
  duration: number;
}

export const useNotificationsStore = defineStore('notifications', () => {
  // State
  const notifications = ref<Notification[]>([]);

  // Actions
  function add(notification: Omit<Notification, 'id'>): void {
    const id = Math.random().toString(36).substring(2, 9);
    const newNotification: Notification = {
      ...notification,
      id,
    };
    notifications.value.push(newNotification);

    // Auto-remove after duration
    if (notification.duration > 0) {
      setTimeout(() => {
        remove(id);
      }, notification.duration);
    }
  }

  function remove(id: string): void {
    const index = notifications.value.findIndex((n) => n.id === id);
    if (index > -1) {
      notifications.value.splice(index, 1);
    }
  }

  function clearAll(): void {
    notifications.value = [];
  }

  // Helper methods for common types
  function success(message: string, title = 'Éxito', duration = 5000): void {
    add({ type: 'success', title, message, duration });
  }

  function error(message: string, title = 'Error', duration = 8000): void {
    add({ type: 'error', title, message, duration });
  }

  function warning(message: string, title = 'Advertencia', duration = 6000): void {
    add({ type: 'warning', title, message, duration });
  }

  function info(message: string, title = 'Información', duration = 5000): void {
    add({ type: 'info', title, message, duration });
  }

  return {
    notifications,
    add,
    remove,
    clearAll,
    success,
    error,
    warning,
    info,
  };
});
