<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import PlatformIcon from '@/Components/shared/PlatformIcon.vue';
import gamesRoute from '@/routes/games';
import { GameData } from '@/types/generated';
import { TicketIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

interface Props {
  game: GameData;
}

defineProps<Props>();
</script>

<template>
  <div
    class="game-card group relative min-w-0"
    :class="{ 'has-keys': game.keyCount !== undefined && game.keyCount > 0 }"
  >
    <Link
      :href="gamesRoute.show.url(game.id)"
      class="block w-full h-full relative"
    >
      <div class="absolute top-2 left-2 z-10 flex gap-1 bg-black/60 backdrop-blur-sm rounded-md px-1.5 py-1">
        <template v-if="game.platforms?.length">
          <PlatformIcon
            v-for="platform in game.platforms"
            :key="platform.id"
            :icon="platform.icon"
            size="sm"
            class="text-white"
          />
        </template>
        <ExclamationTriangleIcon
          v-else
          class="w-4 h-4 text-red-400"
        />
      </div>
      <img
        v-if="game.image"
        :src="game.image"
        :alt="game.name"
        class="game-image"
      >
      <div
        v-else
        class="game-image-placeholder flex items-center justify-center"
      >
        <TicketIcon class="w-10 h-10 text-dark-500" />
      </div>
      <div class="game-overlay">
        {{ game.name }}
      </div>
    </Link>
  </div>
</template>

<style scoped>
.game-card {
    @apply relative w-full bg-dark-900 rounded-lg border border-white/10 flex justify-center items-center transition-colors duration-200 overflow-hidden;
    @apply hover:border-white/25;
    aspect-ratio: 3 / 4;
}

.game-image {
    @apply block w-full h-full object-cover rounded-lg;
}

.game-image-placeholder {
    @apply min-h-full relative w-full bg-dark-900;
}

.game-overlay {
    @apply absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/70 to-transparent text-white text-sm font-medium text-center px-3 py-4 flex items-end justify-center rounded-b-lg break-words overflow-hidden;
    min-height: 40%;
}
</style>
