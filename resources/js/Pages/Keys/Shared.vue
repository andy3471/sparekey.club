<script setup lang="ts">
import { Head, InfiniteScroll, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import UserKeyCard from '@/Components/keys/UserKeyCard.vue';
import EmptyState from '@/Components/shared/EmptyState.vue';
import { KeyData } from '@/types/generated';
import type { Paginated } from '@/types/global';
import { TicketIcon } from '@heroicons/vue/24/outline';
import keysRoutes from '@/routes/keys';

interface Props {
  keys: Paginated<KeyData>;
}

defineProps<Props>();
</script>

<template>
  <AppLayout title="Shared Keys">
    <Head title="Shared Keys" />

    <InfiniteScroll
      v-if="keys.data.length > 0"
      data="keys"
      preserve-url
    >
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
        <UserKeyCard
          v-for="keyItem in keys.data"
          :key="keyItem.id"
          :key-data="keyItem"
        />
      </div>
    </InfiniteScroll>

    <EmptyState
      v-else
      title="No shared keys yet"
      message="Keys you share will appear here"
    >
      <template #icon>
        <TicketIcon class="w-10 h-10 text-white/25" />
      </template>
      <template #action>
        <Link
          :href="keysRoutes.create.url()"
          class="btn-primary mt-3"
        >
          Add a key
        </Link>
      </template>
    </EmptyState>
  </AppLayout>
</template>
