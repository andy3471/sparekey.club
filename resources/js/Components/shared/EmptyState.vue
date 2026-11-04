<script setup lang="ts">
interface Props {
  title: string;
  message?: string;
}

withDefaults(defineProps<Props>(), {
  message: '',
});
</script>

<template>
  <div class="border border-dashed border-white/15 bg-dark-950/40 px-6 py-10 text-center">
    <div class="inline-flex flex-col items-center gap-3">
      <slot name="icon" />
      <div>
        <p class="text-base font-medium text-white">
          {{ title }}
        </p>
        <p
          v-if="message"
          class="mt-1 text-sm text-white/45"
        >
          {{ message }}
        </p>
        <slot name="action" />
      </div>
    </div>
  </div>
</template>
