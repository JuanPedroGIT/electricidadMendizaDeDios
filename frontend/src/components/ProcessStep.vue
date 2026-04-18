<script setup lang="ts">
defineProps<{
  index: number
  title: string
  detail: string
}>();
</script>

<template>
  <div class="process-step">
    <div class="process-step-number">{{ index }}</div>
    <div>
      <h3 style="margin-bottom: 4px">{{ title }}</h3>
      <p>{{ detail }}</p>
    </div>
  </div>
</template>
