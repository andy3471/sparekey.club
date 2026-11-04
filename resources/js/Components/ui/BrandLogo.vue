<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

withDefaults(defineProps<{
  href?: string;
  size?: 'sm' | 'md' | 'lg';
  as?: 'link' | 'span';
}>(), {
  href: '/',
  size: 'md',
  as: 'link',
});

const sizeClass = {
  sm: 'text-base',
  md: 'text-lg',
  lg: 'text-2xl',
};
</script>

<template>
  <Link
    v-if="as === 'link'"
    :href="href"
    class="brand-logo"
    :class="sizeClass[size]"
  >
    Sparekey.club
  </Link>
  <span
    v-else
    class="brand-logo"
    :class="sizeClass[size]"
  >
    Sparekey.club
  </span>
</template>
