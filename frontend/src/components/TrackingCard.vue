<script setup lang="ts">
defineProps<{
  highlights: string[]
}>();
</script>

<template>
  <div class="tracking-card">
    <div>
      <div class="badge" style="background: rgba(255, 255, 255, 0.12); color: #fff">Seguimiento diario</div>
      <h2 style="color: #fff; margin-top: var(--space-3)">Sin sorpresas durante la obra</h2>
      <p style="color: rgba(255, 255, 255, 0.82)">
        Checklist diario, fotos y próximos pasos para que sepas qué ocurrió hoy sin desplazarte.
      </p>
    </div>
    <div class="tracking-grid">
      <div v-for="item in highlights" :key="item" class="tracking-item">
        <p style="color: #fff">{{ item }}</p>
      </div>
    </div>
    <slot />
  </div>
</template>
