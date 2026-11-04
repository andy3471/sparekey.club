<script setup lang="ts">
import KarmaBadge from './KarmaBadge.vue';

interface Props {
  avatar?: string | null;
  name: string;
  karma?: number | null;
  karmaColour?: string | null;
  size?: 'sm' | 'md' | 'lg' | 'xl';
  showKarma?: boolean;
  tooltipPosition?: 'above' | 'below';
}

withDefaults(defineProps<Props>(), {
  avatar: null,
  karma: 0,
  karmaColour: '',
  size: 'md',
  showKarma: true,
  tooltipPosition: 'above',
});

const sizeClasses: Record<string, string> = {
  sm: 'w-5 h-5',
  md: 'w-10 h-10 border border-white/10',
  lg: 'w-20 h-20 border border-white/10',
  xl: 'w-24 h-24 border border-white/10',
};

const badgePositionClasses: Record<string, string> = {
  sm: '-bottom-0.5 -right-0.5',
  md: '-bottom-1 -right-1',
  lg: '-bottom-2 -right-2',
  xl: '-bottom-2 -right-2',
};

const badgeSizeMap: Record<string, 'sm' | 'md' | 'lg'> = {
  sm: 'sm',
  md: 'sm',
  lg: 'md',
  xl: 'lg',
};
</script>

<template>
  <div class="relative flex-shrink-0">
    <img
      :src="avatar || '/images/defaultpic.jpg'"
      :alt="name"
      class="rounded-full object-cover"
      :class="sizeClasses[size]"
    >
    <div
      v-if="showKarma && karmaColour"
      class="absolute"
      :class="badgePositionClasses[size]"
    >
      <KarmaBadge
        :karma="karma"
        :karma-colour="karmaColour"
        :size="badgeSizeMap[size]"
        :tooltip-position="tooltipPosition"
      />
    </div>
  </div>
</template>
