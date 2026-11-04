<script setup lang="ts">
import AppDialog from '@/Components/ui/AppDialog.vue';
import { ExclamationTriangleIcon, QuestionMarkCircleIcon } from '@heroicons/vue/24/outline';

interface Props {
  open: boolean;
  title?: string;
  message?: string;
  confirmLabel?: string;
  cancelLabel?: string;
  variant?: 'danger' | 'warning' | 'default';
}

withDefaults(defineProps<Props>(), {
  title: 'Are you sure?',
  message: '',
  confirmLabel: 'Confirm',
  cancelLabel: 'Cancel',
  variant: 'default',
});

const emit = defineEmits<{
  confirm: [];
  cancel: [];
}>();

const variantClasses: Record<string, string> = {
  danger: 'btn-danger',
  warning: 'border-2 border-warning bg-warning text-dark-950 hover:bg-warning-dark',
  default: 'btn-primary',
};

const variantIconClasses: Record<string, string> = {
  danger: 'text-danger-400 bg-danger-600/20',
  warning: 'text-warning bg-warning/20',
  default: 'text-accent-400 bg-accent-600/20',
};
</script>

<template>
  <AppDialog
    :open="open"
    :title="title"
    @close="emit('cancel')"
  >
    <div class="flex items-start gap-4 mt-1">
      <div
        class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
        :class="variantIconClasses[variant]"
      >
        <ExclamationTriangleIcon
          v-if="variant === 'danger' || variant === 'warning'"
          class="w-5 h-5"
        />
        <QuestionMarkCircleIcon
          v-else
          class="w-5 h-5"
        />
      </div>

      <div class="flex-1 min-w-0">
        <p
          v-if="message"
          class="text-sm text-gray-400"
        >
          {{ message }}
        </p>
        <slot />
      </div>
    </div>

    <div class="flex justify-end gap-3 mt-6">
      <button
        type="button"
        class="btn-ghost"
        @click="emit('cancel')"
      >
        {{ cancelLabel }}
      </button>
      <button
        type="button"
        :class="variantClasses[variant]"
        @click="emit('confirm')"
      >
        {{ confirmLabel }}
      </button>
    </div>
  </AppDialog>
</template>
