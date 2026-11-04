<script setup lang="ts">
import { computed, watch } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import GameBanner from '@/Components/shared/GameBanner.vue';
import ScreenshotGallery from '@/Components/games/ScreenshotGallery.vue';
import GameList from '@/Components/games/GameList.vue';
import KeyCard from '@/Components/keys/KeyCard.vue';
import EmptyState from '@/Components/shared/EmptyState.vue';
import ParentGameLink from '@/Components/shared/ParentGameLink.vue';
import type { GameData, KeyData } from '@/types/generated';
import type { AuthUser, Paginated } from '@/types/global';
import gamesRoute from '@/routes/games';
import keysRoute from '@/routes/keys';
import { useCountdown } from '@/Composables/useCountdown';
import { PlusIcon, KeyIcon, ClockIcon } from '@heroicons/vue/24/outline';

interface Props {
  game: GameData;
  keys: KeyData[];
  childGames?: Paginated<GameData>;
  parentGame?: GameData;
}

const props = defineProps<Props>();

const page = usePage();
const auth = (page.props.auth as AuthUser | undefined) ?? { user: null };
const activeGroup = computed(() => page.props.activeGroup);

const cooldownEndsAt = computed(() => {
  if (!activeGroup.value) return null;

  for (const key of props.keys) {
    if (key.can?.claimDeniedReason === 'cooldown_active' && key.can.cooldownEndsAt) {
      return key.can.cooldownEndsAt;
    }
  }
  return null;
});

const { isExpired: cooldownExpired, formatted: cooldownFormatted } = useCountdown(cooldownEndsAt.value);
watch(cooldownExpired, (expired) => {
  if (expired && cooldownEndsAt.value) {
    router.reload({ only: ['keys'] });
  }
});
</script>

<template>
  <AppLayout>
    <Head :title="game.name" />

    <div class="space-y-5">
      <div class="flex items-center justify-between gap-3">
        <Link
          :href="gamesRoute.index.url()"
          class="text-xs text-white/45 hover:text-white transition-colors"
        >
          All games
        </Link>
      </div>

      <ParentGameLink
        v-if="parentGame"
        :parent-game="parentGame"
      />

      <GameBanner
        :game="game"
        show-rating
        show-genres
      />

      <div class="flex items-center justify-between gap-3 pt-1">
        <h2 class="text-sm font-medium uppercase tracking-wider text-white/40">
          Available Keys
        </h2>
        <Link
          v-if="auth.user"
          :href="keysRoute.create.url({ query: { game_id: game.id } })"
          class="btn-primary"
        >
          <PlusIcon class="w-4 h-4" />
          Add a Key
        </Link>
      </div>

      <div
        v-if="cooldownEndsAt && !cooldownExpired"
        class="rounded-lg border border-accent-600/25 bg-accent-600/10 p-3"
      >
        <div class="flex items-center gap-2.5">
          <ClockIcon class="w-4 h-4 text-accent-400 shrink-0" />
          <p class="text-sm text-white/70">
            Claim cooldown active. You can claim another key in <span class="text-white font-semibold tabular-nums">{{ cooldownFormatted }}</span>
          </p>
        </div>
      </div>

      <EmptyState
        v-if="keys.length === 0"
        title="No keys available"
        message="Be the first to share a key for this game!"
      >
        <template #icon>
          <KeyIcon class="w-10 h-10 text-white/25" />
        </template>
      </EmptyState>

      <div
        v-else
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"
      >
        <KeyCard
          v-for="keyItem in keys"
          :key="keyItem.id"
          :key-data="keyItem"
        />
      </div>

      <div
        v-if="childGames && childGames.data && childGames.data.length > 0"
        class="pt-2 space-y-3"
      >
        <h2 class="text-sm font-medium uppercase tracking-wider text-white/40">
          DLC / Expansions
        </h2>
        <GameList
          :games="childGames"
          scroll-prop="childGames"
        />
      </div>

      <ScreenshotGallery
        v-if="game.screenshots && game.screenshots.length > 0"
        :screenshots="game.screenshots"
      />
    </div>
  </AppLayout>
</template>
