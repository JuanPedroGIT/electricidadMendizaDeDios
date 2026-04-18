<script setup lang="ts">
import { ref, onMounted, computed, onBeforeUnmount, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAdminAuthStore } from '@/stores/adminAuth';
import { useNotificationsStore } from '@/stores/notifications';
import { adminApi, type SiteSettingsDto } from '@/api/admin';

const router = useRouter();
const authStore = useAdminAuthStore();
const notifications = useNotificationsStore();

const settings = ref<SiteSettingsDto | null>(null);
const isLoading = ref(false);
const isSaving = ref(false);
const error = ref<string | null>(null);
const successMessage = ref<string | null>(null);
const hasUnsavedChanges = ref(false);

const formData = ref<SiteSettingsDto>({
  brand: '',
  tagline: '',
  phone: '',
  phone_display: '',
  whatsapp: '',
  email: '',
  address: '',
  cta: '',
});

// Track original data for dirty checking
const originalData = ref<SiteSettingsDto | null>(null);

// Watch for changes
watch(formData, (newVal) => {
  if (originalData.value) {
    hasUnsavedChanges.value = JSON.stringify(newVal) !== JSON.stringify(originalData.value);
  }
}, { deep: true });

// Handle browser unload
const handleBeforeUnload = (e: BeforeUnloadEvent) => {
  if (hasUnsavedChanges.value) {
    e.preventDefault();
    e.returnValue = '';
  }
};

onMounted(() => {
  authStore.restoreSession();
  window.addEventListener('beforeunload', handleBeforeUnload);
  if (!authStore.isAuthenticated) {
    router.push('/admin/login');
    return;
  }
  loadSettings();
});

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', handleBeforeUnload);
});

async function loadSettings() {
  isLoading.value = true;
  error.value = null;
  successMessage.value = null;

  try {
    const response = await adminApi.getSiteSettings();
    settings.value = response.data;
    formData.value = { ...response.data };
    originalData.value = { ...response.data };
    hasUnsavedChanges.value = false;
  } catch (err) {
    if (err instanceof Error && err.message === 'Session expired') {
      authStore.handleSessionExpired();
      router.push('/admin/login');
      return;
    }
    error.value = err instanceof Error ? err.message : 'Error cargando configuración';
  } finally {
    isLoading.value = false;
  }
}

async function handleSubmit() {
  error.value = null;
  successMessage.value = null;

  // Validation
  const required = ['brand', 'tagline', 'phone', 'phone_display', 'email', 'address', 'cta'];
  const missing = required.filter(field => !formData.value[field as keyof SiteSettingsDto]?.trim());

  if (missing.length > 0) {
    error.value = `Campos obligatorios: ${missing.join(', ')}`;
    return;
  }

  isSaving.value = true;

  try {
    await adminApi.updateSiteSettings(formData.value);
    settings.value = { ...formData.value };
    successMessage.value = 'Configuración guardada correctamente';
    notifications.success('Configuración guardada correctamente');
    setTimeout(() => {
      successMessage.value = null;
    }, 3000);
  } catch (err) {
    if (err instanceof Error && err.message === 'Session expired') {
      authStore.handleSessionExpired();
      router.push('/admin/login');
      return;
    }
    error.value = err instanceof Error ? err.message : 'Error guardando configuración';
    notifications.error('No se pudo guardar la configuración');
  } finally {
    isSaving.value = false;
  }
}

function resetForm() {
  if (settings.value) {
    formData.value = { ...settings.value };
  }
  error.value = null;
  successMessage.value = null;
}

function formatPhone() {
  // Auto-format: remove spaces and keep only numbers for storage
  const clean = formData.value.phone.replace(/\s/g, '').replace(/[^\d+]/g, '');
  if (clean !== formData.value.phone) {
    formData.value.phone = clean;
  }
}
</script>

<template>
  <div class="admin-page">
    <header class="page-header">
      <h2>Configuración del Sitio</h2>
    </header>

    <div v-if="isLoading" class="loading">Cargando...</div>

    <div v-else-if="error && !settings" class="error-box">
      {{ error }}
      <button @click="loadSettings">Reintentar</button>
    </div>

    <div v-else class="settings-form">
      <div v-if="error" class="form-error-banner">{{ error }}</div>
      <div v-if="successMessage" class="form-success-banner">{{ successMessage }}</div>

      <form @submit.prevent="handleSubmit">
        <div class="form-section">
          <h3>Información General</h3>

          <div class="form-row">
            <div class="form-group">
              <label for="brand">Nombre de la marca *</label>
              <input
                id="brand"
                v-model="formData.brand"
                type="text"
                placeholder="Ej: Electricidad Mendoza de Dios"
                required
              />
            </div>

            <div class="form-group">
              <label for="tagline">Eslogan *</label>
              <input
                id="tagline"
                v-model="formData.tagline"
                type="text"
                placeholder="Ej: Tu electricista de confianza"
                required
              />
            </div>
          </div>
        </div>

        <div class="form-section">
          <h3>Contacto</h3>

          <div class="form-row">
            <div class="form-group">
              <label for="phone">Teléfono (formato limpio) *</label>
              <input
                id="phone"
                v-model="formData.phone"
                type="tel"
                placeholder="Ej: +34612345678"
                required
                @blur="formatPhone"
              />
              <small>Usado para enlaces tel: y WhatsApp</small>
            </div>

            <div class="form-group">
              <label for="phone_display">Teléfono (formato visual) *</label>
              <input
                id="phone_display"
                v-model="formData.phone_display"
                type="text"
                placeholder="Ej: 612 345 678"
                required
              />
              <small>Como se muestra en la web</small>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="whatsapp">Número WhatsApp *</label>
              <input
                id="whatsapp"
                v-model="formData.whatsapp"
                type="tel"
                placeholder="Ej: 34612345678"
                required
              />
              <small>Sin el + inicial</small>
            </div>

            <div class="form-group">
              <label for="email">Email de contacto *</label>
              <input
                id="email"
                v-model="formData.email"
                type="email"
                placeholder="Ej: info@electricidadmendozadedios.es"
                required
              />
            </div>
          </div>

          <div class="form-group full-width">
            <label for="address">Dirección *</label>
            <input
              id="address"
              v-model="formData.address"
              type="text"
              placeholder="Dirección completa"
              required
            />
          </div>
        </div>

        <div class="form-section">
          <h3>Llamada a la Acción</h3>

          <div class="form-group full-width">
            <label for="cta">Texto del CTA principal *</label>
            <input
              id="cta"
              v-model="formData.cta"
              type="text"
              placeholder="Ej: Solicita tu presupuesto sin compromiso"
              required
            />
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-secondary" @click="resetForm">
            Restablecer
          </button>
          <button type="submit" class="btn-primary" :disabled="isSaving">
            <span v-if="isSaving">Guardando...</span>
            <span v-else>Guardar cambios</span>
          </button>
        </div>
      </form>

      <div class="preview-section">
        <h3>Vista previa</h3>
        <div class="preview-card">
          <div class="preview-row">
            <strong>Marca:</strong>
            <span>{{ formData.brand || '-' }}</span>
          </div>
          <div class="preview-row">
            <strong>Teléfono:</strong>
            <span>{{ formData.phone_display || '-' }}</span>
          </div>
          <div class="preview-row">
            <strong>Email:</strong>
            <span>{{ formData.email || '-' }}</span>
          </div>
          <div class="preview-row">
            <strong>CTA:</strong>
            <span>{{ formData.cta || '-' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page {
  max-width: 900px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.page-header h2 {
  margin: 0;
  color: #1e3a5f;
}

.loading {
  text-align: center;
  padding: 3rem;
  color: #666;
}

.error-box {
  background: #fee;
  color: #c33;
  padding: 1rem;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.error-box button {
  background: #c33;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
}

.settings-form {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.form-error-banner {
  background: #fee;
  color: #c33;
  padding: 1rem;
  border-radius: 6px;
  margin-bottom: 1.5rem;
}

.form-success-banner {
  background: #efe;
  color: #3c3;
  padding: 1rem;
  border-radius: 6px;
  margin-bottom: 1.5rem;
}

.form-section {
  margin-bottom: 2rem;
  padding-bottom: 2rem;
  border-bottom: 1px solid #eee;
}

.form-section:last-of-type {
  border-bottom: none;
  margin-bottom: 1rem;
}

.form-section h3 {
  margin: 0 0 1rem;
  color: #1e3a5f;
  font-size: 1.1rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  margin-bottom: 0.5rem;
  color: #333;
  font-weight: 500;
  font-size: 0.9rem;
}

.form-group input {
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  box-sizing: border-box;
}

.form-group input:focus {
  outline: none;
  border-color: #1e3a5f;
}

.form-group small {
  color: #888;
  font-size: 0.8rem;
  margin-top: 0.25rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 1.5rem;
  border-top: 1px solid #eee;
}

.btn-primary {
  background: #1e3a5f;
  color: white;
  border: none;
  padding: 0.875rem 1.5rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  min-width: 140px;
}

.btn-primary:hover:not(:disabled) {
  background: #2d5a87;
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  padding: 0.875rem 1.5rem;
  background: #f0f0f0;
  border: 1px solid #ddd;
  border-radius: 6px;
  cursor: pointer;
  color: #333;
}

.btn-secondary:hover {
  background: #e0e0e0;
}

.preview-section {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #eee;
}

.preview-section h3 {
  margin: 0 0 1rem;
  color: #666;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.preview-card {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 1.5rem;
}

.preview-row {
  display: flex;
  margin-bottom: 0.75rem;
}

.preview-row:last-child {
  margin-bottom: 0;
}

.preview-row strong {
  width: 100px;
  color: #666;
  flex-shrink: 0;
}

.preview-row span {
  color: #333;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn-primary,
  .btn-secondary {
    width: 100%;
  }
}
</style>
