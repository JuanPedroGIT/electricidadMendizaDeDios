<script setup lang="ts">
defineProps<{
  question: string
  answer: string
}>();
</script>

<template>
  <div class="faq-item">
    <h3 style="margin-bottom: 6px">{{ question }}</h3>
    <p>{{ answer }}</p>
  </div>
</template>
