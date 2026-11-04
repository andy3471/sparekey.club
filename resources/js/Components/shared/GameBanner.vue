<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GameImagePlaceholder from '@/Components/shared/GameImagePlaceholder.vue';
import PlatformIcon from '@/Components/shared/PlatformIcon.vue';
import games from '@/routes/games';
import { GameData } from '@/types/generated';
import { StarIcon } from '@heroicons/vue/24/solid';

interface Props {
  game: GameData;
  linkable?: boolean;
  platformName?: string;
  platformIcon?: string;
  showRating?: boolean;
  showGenres?: boolean;
}

withDefaults(defineProps<Props>(), {
  linkable: false,
  platformName: undefined,
  platformIcon: undefined,
  showRating: false,
  showGenres: false,
});
</script>

<template>
  <div class="surface p-5 sm:p-6">
    <div class="flex flex-col sm:flex-row gap-5">
      <div class="shrink-0">
        <component
          :is="linkable ? Link : 'div'"
          v-bind="linkable ? { href: games.show.url(game.id), class: 'block' } : {}"
        >
          <div class="w-[140px]">
            <img
              v-if="game.image"
              :src="game.image"
              :alt="game.name"
              class="w-full rounded-lg border border-white/10 object-cover aspect-[2/3]"
              :class="linkable ? 'hover:border-white/25 transition-colors' : ''"
            >
            <div
              v-else
              class="w-full aspect-[2/3]"
            >
              <GameImagePlaceholder size="lg" />
            </div>
          </div>
        </component>
      </div>

      <div class="flex-1 min-w-0">
        <component
          :is="linkable ? Link : 'div'"
          v-bind="linkable ? { href: games.show.url(game.id), class: 'block mb-2' } : { class: 'mb-2' }"
        >
          <h1
            class="text-xl sm:text-2xl font-semibold tracking-tight text-white break-words"
            :class="linkable ? 'hover:text-accent-400 transition-colors' : ''"
          >
            {{ game.name }}
          </h1>
        </component>

        <div
          v-if="showRating && game?.aggregated_rating"
          class="flex items-center gap-1.5 text-sm text-white/60 mb-2"
        >
          <StarIcon class="w-4 h-4 text-warning" />
          <span class="font-medium text-white">{{ game.aggregated_rating }}</span>
          <span class="text-white/40">({{ game.aggregated_rating_count }} reviews)</span>
        </div>

        <p
          v-if="game.description"
          class="text-sm text-white/55 leading-relaxed line-clamp-4 mb-3"
        >
          {{ game.description }}
        </p>

        <div
          v-if="showGenres && game.genres && game.genres.length > 0"
          class="flex flex-wrap gap-1.5 mb-3"
        >
          <span
            v-for="genre in game.genres"
            :key="genre.id"
            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-white/5 text-white/60 border border-white/10"
          >
            {{ genre.name }}
          </span>
        </div>

        <div
          v-if="platformName"
          class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-md border border-white/10 bg-white/5"
        >
          <PlatformIcon
            :icon="platformIcon || 'generic'"
            size="sm"
            class="text-white/70"
          />
          <span class="text-sm font-medium text-white/80">{{ platformName }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
