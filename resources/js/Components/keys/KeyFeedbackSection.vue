<script setup lang="ts">
import type { KeyFeedback } from '@/types/generated';
import { HandThumbUpIcon, HandThumbDownIcon } from '@heroicons/vue/24/outline';

interface Props {
  feedback: KeyFeedback | null;
  canFeedback: boolean;
}

defineProps<Props>();

const emit = defineEmits<{
  submit: [value: KeyFeedback];
}>();
</script>

<template>
  <div
    v-if="canFeedback"
    class="pt-4 border-t border-white/10"
  >
    <p class="text-sm text-white/45 mb-3 text-center">
      Did this key work?
    </p>

    <div
      v-if="feedback === null"
      class="flex items-center justify-center gap-2.5"
    >
      <button
        type="button"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-success/10 border border-success/30 rounded-lg text-success hover:bg-success/20 transition-colors text-sm font-medium"
        title="The key redeemed successfully"
        @click="emit('submit', 'worked')"
      >
        <HandThumbUpIcon class="w-4 h-4" />
        It worked
      </button>
      <button
        type="button"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-danger/10 border border-danger/30 rounded-lg text-danger hover:bg-danger/20 transition-colors text-sm font-medium"
        title="The key was invalid or already used"
        @click="emit('submit', 'did_not_work')"
      >
        <HandThumbDownIcon class="w-4 h-4" />
        It didn't work
      </button>
    </div>

    <div
      v-else
      class="flex items-center justify-center"
    >
      <div
        v-if="feedback === 'worked'"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-success/10 border border-success/30 rounded-lg text-success text-sm font-medium"
      >
        <HandThumbUpIcon class="w-4 h-4" />
        You marked this key as working
      </div>
      <div
        v-else
        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-danger/10 border border-danger/30 rounded-lg text-danger text-sm font-medium"
      >
        <HandThumbDownIcon class="w-4 h-4" />
        You reported this key as not working
      </div>
    </div>
  </div>
</template>
