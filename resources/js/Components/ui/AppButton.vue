<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = withDefaults(defineProps<{
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger';
  size?: 'sm' | 'md' | 'lg';
  href?: string;
  type?: 'button' | 'submit' | 'reset';
  disabled?: boolean;
  block?: boolean;
}>(), {
  variant: 'primary',
  size: 'md',
  href: undefined,
  type: 'button',
  disabled: false,
  block: false,
});

const variantClass = computed(() => {
  const map = {
    primary: 'btn-primary',
    secondary: 'btn-secondary',
    ghost: 'btn-ghost',
    danger: 'btn-danger',
  };
  return map[props.variant];
});

const sizeClass = computed(() => {
  if (props.size === 'sm') {
    return 'min-h-8 px-2.5 py-1 text-sm';
  }
  if (props.size === 'lg') {
    return 'min-h-10 px-4 py-2 text-sm';
  }
  return 'min-h-9';
});
</script>

<template>
  <Link
    v-if="href && !disabled"
    :href="href"
    :class="[variantClass, sizeClass, block ? 'w-full' : '']"
  >
    <slot />
  </Link>
  <button
    v-else
    :type="type"
    :disabled="disabled"
    :class="[variantClass, sizeClass, block ? 'w-full' : '']"
  >
    <slot />
  </button>
</template>
