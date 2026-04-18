<script setup lang="ts">
import { ref, onMounted, computed, onBeforeUnmount, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAdminAuthStore } from '@/stores/adminAuth';
import { useNotificationsStore } from '@/stores/notifications';
import { adminApi, type AdminServiceDto, type CreateServicePayload } from '@/api/admin';

const router = useRouter();
const authStore = useAdminAuthStore();
const notifications = useNotificationsStore();

const services = ref<AdminServiceDto[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);
const showForm = ref(false);
const editingService = ref<AdminServiceDto | null>(null);
const formError = ref<string | null>(null);
const isSubmitting = ref(false);

const formData = ref<CreateServicePayload>({
  slug: '',
  name: '',
  summary: '',
  benefits: [''],
});

// Track original data for dirty checking
const originalData = ref<CreateServicePayload | null>(null);
const isDirty = computed(() => {
  if (!originalData.value) return false;
  return JSON.stringify(formData.value) !== JSON.stringify(originalData.value);
});

// Handle browser unload
const handleBeforeUnload = (e: BeforeUnloadEvent) => {
  if (showForm.value && isDirty.value) {
    e.preventDefault();
    e.returnValue = '';
  }
};

onMounted(() => {
  authStore.restoreSession();
  if (!authStore.isAuthenticated) {
    router.push('/admin/login');
    return;
  }
  loadServices();
  window.addEventListener('beforeunload', handleBeforeUnload);
});

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', handleBeforeUnload);
});

async function loadServices() {
  isLoading.value = true;
  error.value = null;

  try {
    const response = await adminApi.getServices();
    services.value = response.data;
  } catch (err) {
    if (err instanceof Error && err.message === 'Session expired') {
      authStore.handleSessionExpired();
      router.push('/admin/login');
      return;
    }
    error.value = err instanceof Error ? err.message : 'Error cargando servicios';
  } finally {
    isLoading.value = false;
  }
}

function createNew() {
  editingService.value = null;
  formData.value = {
    slug: '',
    name: '',
    summary: '',
    benefits: [''],
  };
  originalData.value = { ...formData.value };
  formError.value = null;
  showForm.value = true;
}

function edit(service: AdminServiceDto) {
  editingService.value = service;
  formData.value = {
    slug: service.slug,
    name: service.name,
    summary: service.summary,
    benefits: [...service.benefits],
  };
  originalData.value = { ...formData.value };
  formError.value = null;
  showForm.value = true;
}

function closeForm() {
  if (isDirty.value) {
    const confirmed = confirm('Tienes cambios sin guardar. ¿Deseas cerrar sin guardar?');
    if (!confirmed) return;
  }
  showForm.value = false;
  editingService.value = null;
  originalData.value = null;
}

function addBenefit() {
  formData.value.benefits.push('');
}

function removeBenefit(index: number) {
  formData.value.benefits.splice(index, 1);
  if (formData.value.benefits.length === 0) {
    formData.value.benefits.push('');
  }
}

async function handleSubmit() {
  formError.value = null;

  // Validation
  if (!formData.value.slug.trim()) {
    formError.value = 'El slug es obligatorio';
    return;
  }
  if (!formData.value.name.trim()) {
    formError.value = 'El nombre es obligatorio';
    return;
  }
  if (!formData.value.summary.trim()) {
    formError.value = 'El resumen es obligatorio';
    return;
  }
  const validBenefits = formData.value.benefits.filter(b => b.trim());
  if (validBenefits.length === 0) {
    formError.value = 'Debe incluir al menos un beneficio';
    return;
  }

  const payload = {
    ...formData.value,
    benefits: validBenefits,
  };

  isSubmitting.value = true;

  try {
    if (editingService.value) {
      await adminApi.updateService(editingService.value.id, payload);
      notifications.success('Servicio actualizado correctamente');
    } else {
      await adminApi.createService(payload);
      notifications.success('Servicio creado correctamente');
    }
    await loadServices();
    closeForm();
  } catch (err) {
    if (err instanceof Error && err.message === 'Session expired') {
      authStore.handleSessionExpired();
      router.push('/admin/login');
      return;
    }
    formError.value = err instanceof Error ? err.message : 'Error guardando servicio';
    notifications.error('No se pudo guardar el servicio');
  } finally {
    isSubmitting.value = false;
  }
}

function generateSlug() {
  if (!formData.value.name || formData.value.slug) return;
  formData.value.slug = formData.value.name
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^\w\s-]/g, '')
    .replace(/\s+/g, '-');
}

// Delete functionality
const serviceToDelete = ref<AdminServiceDto | null>(null);
const showDeleteConfirm = ref(false);
const isDeleting = ref(false);

function confirmDelete(service: AdminServiceDto) {
  serviceToDelete.value = service;
  showDeleteConfirm.value = true;
}

function closeDeleteModal() {
  serviceToDelete.value = null;
  showDeleteConfirm.value = false;
}

async function handleDelete() {
  if (!serviceToDelete.value) return;

  isDeleting.value = true;
  try {
    await adminApi.deleteService(serviceToDelete.value.id);
    notifications.success(`"${serviceToDelete.value.name}" eliminado`);
    await loadServices();
    closeDeleteModal();
  } catch (err) {
    if (err instanceof Error && err.message === 'Session expired') {
      authStore.handleSessionExpired();
      router.push('/admin/login');
      return;
    }
    error.value = err instanceof Error ? err.message : 'Error eliminando servicio';
    notifications.error('No se pudo eliminar el servicio');
  } finally {
    isDeleting.value = false;
  }
}
</script>

<template>
  <div class="admin-page">
    <header class="page-header">
      <h2>Gestión de Servicios</h2>
      <button class="btn-primary" @click="createNew">
        <span>+</span> Nuevo servicio
      </button>
    </header>

    <div v-if="isLoading" class="loading">Cargando...</div>

    <div v-else-if="error" class="error-box">
      {{ error }}
      <button @click="loadServices">Reintentar</button>
    </div>

    <div v-else-if="services.length === 0" class="empty-state">
      <p>No hay servicios configurados</p>
      <button class="btn-primary" @click="createNew">Crear primer servicio</button>
    </div>

    <div v-else class="services-grid">
      <div v-for="service in services" :key="service.id" class="service-card">
        <div class="service-header">
          <h3>{{ service.name }}</h3>
          <span class="slug">/{{ service.slug }}</span>
        </div>
        <p class="summary">{{ service.summary }}</p>
        <ul class="benefits">
          <li v-for="(benefit, idx) in service.benefits.slice(0, 3)" :key="idx">
            {{ benefit }}
          </li>
          <li v-if="service.benefits.length > 3" class="more">
            +{{ service.benefits.length - 3 }} más...
          </li>
        </ul>
        <div class="actions">
          <button class="btn-edit" @click="edit(service)">Editar</button>
          <button class="btn-delete" @click="confirmDelete(service)">Eliminar</button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteConfirm" class="modal-overlay" @click.self="closeDeleteModal">
      <div class="modal modal-small">
        <header class="modal-header">
          <h3>¿Eliminar servicio?</h3>
          <button class="close-btn" @click="closeDeleteModal">×</button>
        </header>
        <div class="modal-body">
          <p>Esta acción no se puede deshacer.</p>
          <p v-if="serviceToDelete" class="delete-target">
            <strong>{{ serviceToDelete.name }}</strong>
          </p>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-secondary" @click="closeDeleteModal" :disabled="isDeleting">
            Cancelar
          </button>
          <button type="button" class="btn-danger" @click="handleDelete" :disabled="isDeleting">
            <span v-if="isDeleting">Eliminando...</span>
            <span v-else>Sí, eliminar</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Form -->
    <div v-if="showForm" class="modal-overlay" @click.self="closeForm">
      <div class="modal">
        <header class="modal-header">
          <h3>{{ editingService ? 'Editar servicio' : 'Nuevo servicio' }}</h3>
          <button class="close-btn" @click="closeForm">×</button>
        </header>

        <form @submit.prevent="handleSubmit">
          <div v-if="formError" class="form-error">{{ formError }}</div>

          <div class="form-group">
            <label for="name">Nombre *</label>
            <input
              id="name"
              v-model="formData.name"
              type="text"
              placeholder="Ej: Reformas integrales"
              required
              @blur="generateSlug"
            />
          </div>

          <div class="form-group">
            <label for="slug">Slug (URL) *</label>
            <input
              id="slug"
              v-model="formData.slug"
              type="text"
              placeholder="Ej: reformas-integrales"
              required
            />
          </div>

          <div class="form-group">
            <label for="summary">Resumen *</label>
            <textarea
              id="summary"
              v-model="formData.summary"
              rows="3"
              placeholder="Breve descripción del servicio"
              required
            />
          </div>

          <div class="form-group">
            <label>Beneficios *</label>
            <div v-for="(benefit, index) in formData.benefits" :key="index" class="benefit-row">
              <input
                v-model="formData.benefits[index]"
                type="text"
                placeholder="Ej: Sin sorpresas de precios"
              />
              <button
                type="button"
                class="btn-remove"
                @click="removeBenefit(index)"
                :disabled="formData.benefits.length === 1"
              >
                🗑️
              </button>
            </div>
            <button type="button" class="btn-add" @click="addBenefit">
              + Añadir beneficio
            </button>
          </div>

          <div class="modal-actions">
            <button type="button" class="btn-secondary" @click="closeForm">
              Cancelar
            </button>
            <button type="submit" class="btn-primary" :disabled="isSubmitting">
              {{ isSubmitting ? 'Guardando...' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page {
  max-width: 1200px;
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

.btn-primary {
  background: #1e3a5f;
  color: white;
  border: none;
  padding: 0.75rem 1.25rem;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary:hover:not(:disabled) {
  background: #2d5a87;
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.loading,
.empty-state {
  text-align: center;
  padding: 3rem;
  color: #666;
}

.empty-state {
  background: white;
  border-radius: 12px;
  border: 2px dashed #ddd;
}

.empty-state button {
  margin-top: 1rem;
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

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.service-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
}

.service-header {
  margin-bottom: 0.75rem;
}

.service-header h3 {
  margin: 0;
  color: #1e3a5f;
  font-size: 1.25rem;
}

.slug {
  color: #888;
  font-size: 0.85rem;
}

.summary {
  color: #555;
  margin: 0 0 1rem;
  flex: 1;
}

.benefits {
  margin: 0 0 1rem;
  padding-left: 1.25rem;
  font-size: 0.9rem;
  color: #666;
}

.benefits li {
  margin-bottom: 0.25rem;
}

.benefits .more {
  color: #888;
  font-style: italic;
}

.actions {
  display: flex;
  gap: 0.5rem;
}

.btn-edit {
  flex: 1;
  padding: 0.5rem;
  background: #f0f0f0;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  color: #333;
}

.btn-edit:hover {
  background: #e0e0e0;
}

.btn-delete {
  padding: 0.5rem;
  background: #fee;
  border: 1px solid #fcc;
  border-radius: 4px;
  cursor: pointer;
  color: #c33;
}

.btn-delete:hover {
  background: #fcc;
}

/* Modal */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
}

.modal {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid #eee;
}

.modal-header h3 {
  margin: 0;
  color: #1e3a5f;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #888;
}

.close-btn:hover {
  color: #333;
}

form {
  padding: 1.5rem;
}

.form-error {
  background: #fee;
  color: #c33;
  padding: 0.75rem;
  border-radius: 6px;
  margin-bottom: 1rem;
}

.form-group {
  margin-bottom: 1.25rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #333;
  font-weight: 500;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  box-sizing: border-box;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #1e3a5f;
}

.benefit-row {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.benefit-row input {
  flex: 1;
}

.btn-remove {
  padding: 0.5rem;
  background: #fee;
  border: 1px solid #fcc;
  border-radius: 4px;
  cursor: pointer;
}

.btn-remove:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-add {
  background: none;
  border: 1px dashed #1e3a5f;
  color: #1e3a5f;
  padding: 0.5rem;
  border-radius: 4px;
  cursor: pointer;
  width: 100%;
}

.btn-add:hover {
  background: #f0f7ff;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 1rem;
  border-top: 1px solid #eee;
}

.btn-secondary {
  padding: 0.75rem 1.5rem;
  background: #f0f0f0;
  border: 1px solid #ddd;
  border-radius: 6px;
  cursor: pointer;
}

.btn-secondary:hover {
  background: #e0e0e0;
}

/* Delete modal specific styles */
.modal-small {
  max-width: 400px;
}

.modal-body {
  padding: 1.5rem;
  text-align: center;
}

.modal-body p {
  margin: 0 0 0.5rem;
  color: #555;
}

.delete-target {
  background: #f8f8f8;
  padding: 0.75rem;
  border-radius: 6px;
  margin-top: 1rem;
}

.btn-danger {
  padding: 0.75rem 1.5rem;
  background: #c33;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
}

.btn-danger:hover:not(:disabled) {
  background: #a22;
}

.btn-danger:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
