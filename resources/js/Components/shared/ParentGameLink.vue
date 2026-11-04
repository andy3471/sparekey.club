<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GameImagePlaceholder from '@/Components/shared/GameImagePlaceholder.vue';
import games from '@/routes/games';
import type { GameData } from '@/types/generated';

defineProps<{
  parentGame: GameData;
}>();
</script>

<template>
  <div class="surface p-3 flex items-center gap-3">
    <span class="text-xs text-white/45 shrink-0">DLC for</span>
    <Link
      :href="games.show.url(parentGame.id)"
      class="flex items-center gap-2.5 hover:opacity-80 transition-opacity min-w-0"
    >
      <img
        v-if="parentGame.image"
        :src="parentGame.image"
        :alt="parentGame.name"
        class="w-9 h-12 rounded object-cover border border-white/10 shrink-0"
      >
      <div
        v-else
        class="w-9 h-12 shrink-0"
      >
        <GameImagePlaceholder size="sm" />
      </div>
      <span class="text-sm font-medium text-white truncate">{{ parentGame.name }}</span>
    </Link>
  </div>
</template>
