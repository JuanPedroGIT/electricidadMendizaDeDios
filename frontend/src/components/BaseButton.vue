<script setup lang="ts">
defineProps<{
  variant?: 'primary' | 'ghost' | 'secondary'
  href?: string
  type?: 'button' | 'submit' | 'reset'
  disabled?: boolean
}>();
</script>

<template>
  <component
    :is="href ? 'a' : 'button'"
    :href="href"
    :type="href ? undefined : type || 'button'"
    :disabled="disabled"
    class="btn"
    :class="{
      'btn-primary': !variant || variant === 'primary',
      'btn-ghost': variant === 'ghost',
      'btn-secondary': variant === 'secondary',
    }"
  >
    <slot />
  </component>
</template>
