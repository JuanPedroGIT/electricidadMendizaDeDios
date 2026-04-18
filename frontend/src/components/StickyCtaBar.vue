<script setup lang="ts">
import { PHONE_DISPLAY, PHONE_LINK, WHATSAPP_LINK } from '../data/contact'
</script>

<template>
  <div class="sticky-cta" role="group" aria-label="Accesos rápidos de contacto">
    <a class="btn btn-secondary" :href="PHONE_LINK">Llamar {{ PHONE_DISPLAY }}</a>
    <a class="btn btn-primary" :href="WHATSAPP_LINK" target="_blank" rel="noreferrer">WhatsApp</a>
  </div>
</template>

<style scoped>
@media (min-width: 920px) {
  .sticky-cta {
    display: none;
  }
}
</style>
