<script setup lang="ts">
interface Props {
  title: string;
}

defineProps<Props>();
</script>

<template>
  <div class="border-b border-white/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
      <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-white break-words">
        {{ title }}
      </h1>
    </div>
  </div>
</template>
