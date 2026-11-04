<script setup lang="ts">
import { KeyIcon, LockClosedIcon } from '@heroicons/vue/24/outline';

interface Props {
  keyCount: number;
}

defineProps<Props>();
</script>

<template>
  <div
    v-if="keyCount > 0"
    class="absolute top-2 right-2 z-10 bg-success rounded-md p-1.5"
    :title="`${keyCount} ${keyCount === 1 ? 'key' : 'keys'} available`"
  >
    <KeyIcon class="w-4 h-4 text-white" />
    <span class="absolute -top-1.5 -right-1.5 bg-white text-success text-[10px] font-semibold rounded-md min-w-4 h-4 px-1 flex items-center justify-center">
      {{ keyCount }}
    </span>
  </div>
  <div
    v-else
    class="absolute top-2 right-2 z-10 bg-dark-700 border border-white/10 rounded-md p-1.5"
    title="No keys available"
  >
    <LockClosedIcon class="w-4 h-4 text-gray-400" />
  </div>
</template>
