<script setup lang="ts">
defineProps<{
  kicker?: string
  title: string
  description?: string
  id?: string
}>();
</script>

<template>
  <section :id="id" class="section">
    <div class="container">
      <div class="section-header">
        <div v-if="kicker" class="section-kicker">{{ kicker }}</div>
        <h2>{{ title }}</h2>
        <p v-if="description">{{ description }}</p>
      </div>
      <slot />
    </div>
  </section>
</template>
