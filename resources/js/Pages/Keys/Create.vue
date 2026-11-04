<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AddKey from '@/Components/keys/AddKey.vue';
import { AutocompleteGameData, GroupData, PlatformData } from '@/types/generated';
import groupRoutes from '@/routes/groups';

interface Props {
  canCreate: boolean;
  platforms: PlatformData[];
  game?: AutocompleteGameData | null;
  groups: GroupData[];
  activeGroupId?: string | null;
}

defineProps<Props>();
</script>

<template>
  <AppLayout>
    <Head title="Add Key" />
    <div class="max-w-2xl mx-auto">
      <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-white mb-4">
        Add Key
      </h1>

      <template v-if="canCreate">
        <AddKey
          :platforms="platforms"
          :game="game"
          :groups="groups"
          :active-group-id="activeGroupId"
        />
      </template>

      <div
        v-else
        class="surface p-6 text-center"
      >
        <h2 class="text-lg font-semibold text-white mb-2">
          Join a group first
        </h2>
        <p class="text-white/55 mb-6 max-w-sm mx-auto">
          You need to be in at least one group before you can share keys.
        </p>
        <Link
          :href="groupRoutes.index.url()"
          class="btn-primary"
        >
          Browse groups
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
