<script setup lang="ts">
interface Props {
  karma: number | null;
  karmaColour: string | null;
  size?: 'sm' | 'md' | 'lg';
  tooltipPosition?: 'above' | 'below';
}

withDefaults(defineProps<Props>(), {
  size: 'sm',
  tooltipPosition: 'above',
});

const sizeClasses: Record<string, string> = {
  sm: 'min-w-[1.25rem] px-1.5 py-0.5 text-[10px] leading-none',
  md: 'min-w-[1.5rem] px-2 py-0.5 text-xs leading-none',
  lg: 'min-w-[2rem] px-2.5 py-1 text-sm font-bold leading-none',
};
</script>

<template>
  <span class="relative group/karma inline-flex">
    <span
      class="inline-flex items-center justify-center rounded-md font-semibold tabular-nums"
      :class="[karmaColour, sizeClasses[size]]"
      :aria-label="`Karma: ${karma}`"
      role="img"
    >
      <span aria-hidden="true">{{ karma }}</span>
    </span>
    <span
      role="tooltip"
      aria-hidden="true"
      class="pointer-events-none absolute left-1/2 -translate-x-1/2 w-48 rounded-lg surface px-3 py-2 text-xs text-gray-300 text-center opacity-0 group-hover/karma:opacity-100 transition-opacity duration-150 z-50"
      :class="tooltipPosition === 'below' ? 'top-full mt-2' : 'bottom-full mb-2'"
    >
      <span
        v-if="tooltipPosition === 'below'"
        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-px border-4 border-transparent border-b-dark-600"
      />
      Karma: earned by sharing keys and receiving positive feedback
      <span
        v-if="tooltipPosition === 'above'"
        class="absolute top-full left-1/2 -translate-x-1/2 -mt-px border-4 border-transparent border-t-dark-600"
      />
    </span>
  </span>
</template>
