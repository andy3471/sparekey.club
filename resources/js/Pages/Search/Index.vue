<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import GameList from '@/Components/games/GameList.vue';
import { GameData } from '@/types/generated';
import type { Paginated } from '@/types/global';

interface Props {
  title: string;
  games: Paginated<GameData>;
  error?: string;
}

defineProps<Props>();
</script>

<template>
  <AppLayout :title="title">
    <Head :title="title" />

    <div
      v-if="error"
      class="mb-5 rounded-lg border border-danger/40 bg-danger/10 p-3 text-sm text-danger-300"
    >
      {{ error }}
    </div>
    <GameList :games="games" />
  </AppLayout>
</template>
