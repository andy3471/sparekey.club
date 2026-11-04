<script setup lang="ts">
import { InfiniteScroll, Link } from '@inertiajs/vue3';
import GameCard from '@/Components/shared/GameCard.vue';
import EmptyState from '@/Components/shared/EmptyState.vue';
import { GameData } from '@/types/generated';
import type { Paginated } from '@/types/global';
import { FaceFrownIcon, PlusIcon } from '@heroicons/vue/24/outline';
import { create as createKeyRoute } from '@/routes/keys';

interface Props {
  games?: Paginated<GameData>;
  scrollProp?: string;
}

const props = withDefaults(defineProps<Props>(), {
  games: () => ({
    data: [],
    current_page: 1,
    last_page: 1,
    per_page: 12,
    total: 0,
  }),
  scrollProp: 'games',
});
</script>

<template>
  <InfiniteScroll
    v-if="games && games.data"
    :data="props.scrollProp"
    preserve-url
  >
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
      <GameCard
        v-for="game in games.data"
        :key="game.id"
        :game="game"
      />
    </div>

    <EmptyState
      v-if="games.data.length === 0"
      title="Shelf is empty"
      message="Add a spare key, or loosen the platform filter."
    >
      <template #icon>
        <FaceFrownIcon class="w-10 h-10 text-white/25" />
      </template>
      <template #action>
        <Link
          :href="createKeyRoute.url()"
          class="btn-primary mt-3"
        >
          <PlusIcon class="w-4 h-4" />
          Add a Key
        </Link>
      </template>
    </EmptyState>
  </InfiniteScroll>

  <div
    v-else
    class="col-span-full"
  >
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
      <div
        v-for="i in 12"
        :key="i"
        class="relative w-full bg-white/5 border border-white/10 rounded-lg animate-pulse"
        style="aspect-ratio: 3/4;"
      />
    </div>
  </div>
</template>
