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
  <div class="surface p-8 text-center">
    <div class="inline-flex flex-col items-center gap-3">
      <slot name="icon" />
      <div>
        <p class="text-base font-medium text-white">
          {{ title }}
        </p>
        <p
          v-if="message"
          class="text-sm text-white/45 mt-1"
        >
          {{ message }}
        </p>
        <slot name="action" />
      </div>
    </div>
  </div>
</template>
