<script setup lang="ts">
import { useNotificationsStore, type NotificationType } from '@/stores/notifications';

const notificationsStore = useNotificationsStore();

const icons: Record<NotificationType, string> = {
  success: '✓',
  error: '✕',
  warning: '⚠',
  info: 'ℹ',
};

const titles: Record<NotificationType, string> = {
  success: 'Éxito',
  error: 'Error',
  warning: 'Advertencia',
  info: 'Información',
};
</script>

<template>
  <div class="toast-container">
    <TransitionGroup name="toast" tag="div">
      <div
        v-for="notification in notificationsStore.notifications"
        :key="notification.id"
        class="toast"
        :class="`toast--${notification.type}`"
      >
        <div class="toast__icon">
          {{ icons[notification.type] }}
        </div>
        <div class="toast__content">
          <div class="toast__title">{{ notification.title || titles[notification.type] }}</div>
          <div class="toast__message">{{ notification.message }}</div>
        </div>
        <button class="toast__close" @click="notificationsStore.remove(notification.id)">
          ×
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-container {
  position: fixed;
  top: 1rem;
  right: 1rem;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  max-width: 400px;
  pointer-events: none;
}

.toast-container > div {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  pointer-events: auto;
}

.toast {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 1rem;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border-left: 4px solid;
  min-width: 300px;
  max-width: 400px;
}

.toast--success {
  border-left-color: #22c55e;
}

.toast--success .toast__icon {
  color: #22c55e;
}

.toast--error {
  border-left-color: #ef4444;
}

.toast--error .toast__icon {
  color: #ef4444;
}

.toast--warning {
  border-left-color: #f59e0b;
}

.toast--warning .toast__icon {
  color: #f59e0b;
}

.toast--info {
  border-left-color: #3b82f6;
}

.toast--info .toast__icon {
  color: #3b82f6;
}

.toast__icon {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  font-weight: bold;
  flex-shrink: 0;
}

.toast__content {
  flex: 1;
  min-width: 0;
}

.toast__title {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.25rem;
  font-size: 0.95rem;
}

.toast__message {
  color: #6b7280;
  font-size: 0.9rem;
  line-height: 1.4;
  word-wrap: break-word;
}

.toast__close {
  background: none;
  border: none;
  font-size: 1.5rem;
  line-height: 1;
  color: #9ca3af;
  cursor: pointer;
  padding: 0;
  margin: -0.25rem -0.25rem -0.25rem 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: color 0.2s;
}

.toast__close:hover {
  color: #4b5563;
}

/* Transition animations */
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

@media (max-width: 480px) {
  .toast-container {
    left: 1rem;
    right: 1rem;
    max-width: none;
  }

  .toast {
    max-width: none;
    min-width: auto;
  }
}
</style>
