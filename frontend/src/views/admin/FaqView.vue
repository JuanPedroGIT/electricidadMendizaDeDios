<script setup lang="ts">
import { ref, onMounted, computed, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { useAdminAuthStore } from '@/stores/adminAuth';
import { useNotificationsStore } from '@/stores/notifications';
import { adminApi, type AdminFaqDto, type CreateFaqPayload } from '@/api/admin';

const router = useRouter();
const authStore = useAdminAuthStore();
const notifications = useNotificationsStore();

const faqs = ref<AdminFaqDto[]>([]);
const isLoading = ref(false);
const error = ref<string | null>(null);
const showForm = ref(false);
const editingFaq = ref<AdminFaqDto | null>(null);
const formError = ref<string | null>(null);
const isSubmitting = ref(false);

const formData = ref<CreateFaqPayload>({
  question: '',
  answer: '',
});

// Track original data for dirty checking
const originalData = ref<CreateFaqPayload | null>(null);
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
  window.addEventListener('beforeunload', handleBeforeUnload);
  loadFaqs();
});

onBeforeUnmount(() => {
  window.removeEventListener('beforeunload', handleBeforeUnload);
});

async function loadFaqs() {
  isLoading.value = true;
  error.value = null;

  try {
    const response = await adminApi.getFaqs();
    faqs.value = response.data;
  } catch (err) {
    if (err instanceof Error && err.message === 'Session expired') {
      authStore.handleSessionExpired();
      router.push('/admin/login');
      return;
    }
    error.value = err instanceof Error ? err.message : 'Error cargando FAQs';
  } finally {
    isLoading.value = false;
  }
}

function createNew() {
  editingFaq.value = null;
  formData.value = { question: '', answer: '' };
  originalData.value = { ...formData.value };
  formError.value = null;
  showForm.value = true;
}

function edit(faq: AdminFaqDto) {
  editingFaq.value = faq;
  formData.value = { question: faq.question, answer: faq.answer };
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
  editingFaq.value = null;
  originalData.value = null;
}

async function handleSubmit() {
  formError.value = null;

  if (!formData.value.question.trim()) {
    formError.value = 'La pregunta es obligatoria';
    return;
  }
  if (!formData.value.answer.trim()) {
    formError.value = 'La respuesta es obligatoria';
    return;
  }

  isSubmitting.value = true;

  try {
    if (editingFaq.value) {
      await adminApi.updateFaq(editingFaq.value.id, formData.value);
      notifications.success('Pregunta actualizada correctamente');
    } else {
      await adminApi.createFaq(formData.value);
      notifications.success('Pregunta creada correctamente');
    }
    await loadFaqs();
    closeForm();
  } catch (err) {
    if (err instanceof Error && err.message === 'Session expired') {
      authStore.handleSessionExpired();
      router.push('/admin/login');
      return;
    }
    formError.value = err instanceof Error ? err.message : 'Error guardando FAQ';
    notifications.error('No se pudo guardar la pregunta');
  } finally {
    isSubmitting.value = false;
  }
}

// Delete functionality
const faqToDelete = ref<AdminFaqDto | null>(null);
const showDeleteConfirm = ref(false);
const isDeleting = ref(false);

function confirmDelete(faq: AdminFaqDto) {
  faqToDelete.value = faq;
  showDeleteConfirm.value = true;
}

function closeDeleteModal() {
  faqToDelete.value = null;
  showDeleteConfirm.value = false;
}

async function handleDelete() {
  if (!faqToDelete.value) return;

  isDeleting.value = true;
  try {
    await adminApi.deleteFaq(faqToDelete.value.id);
    notifications.success('Pregunta eliminada');
    await loadFaqs();
    closeDeleteModal();
  } catch (err) {
    if (err instanceof Error && err.message === 'Session expired') {
      authStore.handleSessionExpired();
      router.push('/admin/login');
      return;
    }
    error.value = err instanceof Error ? err.message : 'Error eliminando FAQ';
    notifications.error('No se pudo eliminar la pregunta');
  } finally {
    isDeleting.value = false;
  }
}
</script>

<template>
  <div class="admin-page">
    <header class="page-header">
      <h2>Preguntas Frecuentes (FAQ)</h2>
      <button class="btn-primary" @click="createNew">
        <span>+</span> Nueva pregunta
      </button>
    </header>

    <div v-if="isLoading" class="loading">Cargando...</div>

    <div v-else-if="error" class="error-box">
      {{ error }}
      <button @click="loadFaqs">Reintentar</button>
    </div>

    <div v-else-if="faqs.length === 0" class="empty-state">
      <p>No hay preguntas configuradas</p>
      <button class="btn-primary" @click="createNew">Crear primera pregunta</button>
    </div>

    <div v-else class="faq-list">
      <div v-for="(faq, index) in faqs" :key="faq.id" class="faq-card">
        <div class="faq-number">{{ index + 1 }}</div>
        <div class="faq-content">
          <h4>{{ faq.question }}</h4>
          <p>{{ faq.answer }}</p>
        </div>
        <div class="faq-actions">
          <button class="btn-edit" @click="edit(faq)">Editar</button>
          <button class="btn-delete" @click="confirmDelete(faq)">Eliminar</button>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteConfirm" class="modal-overlay" @click.self="closeDeleteModal">
      <div class="modal modal-small">
        <header class="modal-header">
          <h3>¿Eliminar pregunta?</h3>
          <button class="close-btn" @click="closeDeleteModal">×</button>
        </header>
        <div class="modal-body">
          <p>Esta acción no se puede deshacer.</p>
          <p v-if="faqToDelete" class="delete-target">
            <strong>{{ faqToDelete.question }}</strong>
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
          <h3>{{ editingFaq ? 'Editar pregunta' : 'Nueva pregunta' }}</h3>
          <button class="close-btn" @click="closeForm">×</button>
        </header>

        <form @submit.prevent="handleSubmit">
          <div v-if="formError" class="form-error">{{ formError }}</div>

          <div class="form-group">
            <label for="question">Pregunta *</label>
            <input
              id="question"
              v-model="formData.question"
              type="text"
              placeholder="Ej: ¿Cuánto tiempo tarda una reforma?"
              required
            />
          </div>

          <div class="form-group">
            <label for="answer">Respuesta *</label>
            <textarea
              id="answer"
              v-model="formData.answer"
              rows="5"
              placeholder="Escribe la respuesta detallada..."
              required
            />
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

.faq-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.faq-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  display: flex;
  gap: 1rem;
  align-items: flex-start;
}

.faq-number {
  width: 32px;
  height: 32px;
  background: #1e3a5f;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.9rem;
  flex-shrink: 0;
}

.faq-content {
  flex: 1;
}

.faq-content h4 {
  margin: 0 0 0.5rem;
  color: #1e3a5f;
  font-size: 1.1rem;
}

.faq-content p {
  margin: 0;
  color: #555;
  line-height: 1.6;
}

.faq-actions {
  flex-shrink: 0;
  display: flex;
  gap: 0.5rem;
}

.btn-edit {
  padding: 0.5rem 1rem;
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
  max-width: 600px;
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
  font-family: inherit;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #1e3a5f;
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
