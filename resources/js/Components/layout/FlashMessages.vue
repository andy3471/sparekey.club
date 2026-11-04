<script setup lang="ts">
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { FlashProps } from '@/types/global';
import { CheckCircleIcon, XCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const emptyFlash: FlashProps = { message: null, error: null, status: null };

const page = usePage();
const flash = ref<FlashProps>(emptyFlash);
const visible = ref(false);

watch(() => page.props.flash, (newFlash) => {
  flash.value = newFlash;
  visible.value = (flash.value.message != null && flash.value.message !== '') || (flash.value.error != null && flash.value.error !== '');

  if (visible.value) {
    setTimeout(() => {
      visible.value = false;
    }, 5000);
  }
}, { immediate: true });

const dismiss = () => {
  visible.value = false;
};
</script>

<template>
  <transition
    enter-active-class="motion-safe:transition motion-safe:ease-out motion-safe:duration-300"
    enter-from-class="motion-safe:opacity-0 motion-safe:translate-y-2"
    enter-to-class="motion-safe:opacity-100 motion-safe:translate-y-0"
    leave-active-class="motion-safe:transition motion-safe:ease-in motion-safe:duration-200"
    leave-from-class="motion-safe:opacity-100 motion-safe:translate-y-0"
    leave-to-class="motion-safe:opacity-0 motion-safe:translate-y-2"
  >
    <div
      v-if="visible && (flash.message || flash.error)"
      class="fixed bottom-6 right-6 z-50 max-w-sm space-y-2"
      role="status"
      aria-live="polite"
    >
      <div
        v-if="flash.message"
        class="surface border-success/30 p-4 flex items-start gap-3"
      >
        <CheckCircleIcon class="w-5 h-5 text-success flex-shrink-0 mt-0.5" />
        <p class="text-gray-200 text-sm flex-1">
          {{ flash.message }}
        </p>
        <button
          type="button"
          class="text-gray-500 hover:text-white transition-colors"
          aria-label="Dismiss notification"
          @click="dismiss"
        >
          <XMarkIcon class="w-4 h-4" />
        </button>
      </div>

      <div
        v-if="flash.error"
        class="surface border-danger/30 p-4 flex items-start gap-3"
        role="alert"
        aria-live="assertive"
      >
        <XCircleIcon class="w-5 h-5 text-danger flex-shrink-0 mt-0.5" />
        <p class="text-gray-200 text-sm flex-1">
          {{ flash.error }}
        </p>
        <button
          type="button"
          class="text-gray-500 hover:text-white transition-colors"
          aria-label="Dismiss notification"
          @click="dismiss"
        >
          <XMarkIcon class="w-4 h-4" />
        </button>
      </div>
    </div>
  </transition>
</template>
