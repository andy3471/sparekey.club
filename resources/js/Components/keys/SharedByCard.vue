<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import UserAvatar from '@/Components/shared/UserAvatar.vue';
import GroupAvatar from '@/Components/shared/GroupAvatar.vue';
import { PublicUserData, GroupData } from '@/types/generated';
import users from '@/routes/users';

interface Props {
  user: PublicUserData;
  group?: GroupData | null;
}

defineProps<Props>();
</script>

<template>
  <div class="surface p-5 overflow-hidden">
    <h2 class="text-xs font-medium uppercase tracking-wider text-white/40 mb-4">
      Shared By
    </h2>
    <div class="flex flex-col items-center text-center min-w-0">
      <Link
        :href="users.show.url(user.id)"
        class="relative mb-3 shrink-0"
      >
        <UserAvatar
          :avatar="user.avatar"
          :name="user.name"
          :karma="user.karma ?? 0"
          :karma-colour="user.karma_colour ?? ''"
          size="lg"
          class="hover:border-white/25 transition-colors"
        />
      </Link>
      <h3 class="text-base font-medium text-white mb-1 max-w-full truncate">
        <Link
          :href="users.show.url(user.id)"
          class="text-accent-400 hover:text-accent-300 transition-colors"
        >
          {{ user.name }}
        </Link>
      </h3>
      <div
        v-if="group"
        class="flex items-center justify-center gap-1.5 mb-2 max-w-full"
      >
        <GroupAvatar
          :avatar="group.avatar"
          :name="group.name"
          size="xs"
          class="shrink-0"
        />
        <span class="text-white/45 text-sm truncate">{{ group.name }}</span>
      </div>
      <p
        v-if="user.bio"
        class="text-white/45 text-sm line-clamp-3 break-words max-w-full"
      >
        {{ user.bio }}
      </p>
    </div>
  </div>
</template>
