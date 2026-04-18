<script setup lang="ts">
defineProps<{
  name: string
  summary: string
  tags: string[]
}>();
</script>

<template>
  <article class="card service-card">
    <div class="badge" style="margin-bottom: 10px">Ver detalle</div>
    <h3>{{ name }}</h3>
    <p>{{ summary }}</p>
    <div class="hero-bullets" style="margin-top: var(--space-4)">
      <span v-for="tag in tags" :key="tag" class="pill">{{ tag }}</span>
    </div>
  </article>
</template>
