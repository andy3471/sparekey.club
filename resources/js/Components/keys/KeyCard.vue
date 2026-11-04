<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { KeyData } from '@/types/generated';
import keyRoutes from '@/routes/keys';
import UserAvatar from '@/Components/shared/UserAvatar.vue';
import GroupAvatar from '@/Components/shared/GroupAvatar.vue';
import KeyStatusBadge from '@/Components/shared/KeyStatusBadge.vue';
import PlatformIcon from '@/Components/shared/PlatformIcon.vue';

interface Props {
  keyData: KeyData;
}

defineProps<Props>();
</script>

<template>
  <Link
    :href="keyRoutes.show(keyData.id)"
    class="group relative surface p-4 transition-colors duration-200 hover:border-white/25 block"
  >
    <div class="flex items-center justify-between mb-3">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-sm font-medium bg-accent-600/15 text-accent-400 border border-accent-600/25">
        <PlatformIcon
          :icon="keyData.platform?.icon || 'generic'"
          size="sm"
        />
        {{ keyData.platform?.name || 'Unknown Platform' }}
      </span>
      <KeyStatusBadge
        :can-claim="keyData.can?.claim ?? false"
        :claim-denied-reason="keyData.can?.claimDeniedReason ?? null"
      />
    </div>

    <div class="flex items-center space-x-3">
      <UserAvatar
        :avatar="keyData.createdUser?.avatar"
        :name="keyData.createdUser?.name || 'Unknown User'"
        :karma="keyData.createdUser?.karma || 0"
        :karma-colour="keyData.createdUser?.karma_colour || ''"
        size="md"
        class="group-hover:border-accent-500"
      />
      <div class="flex-1 min-w-0">
        <p class="text-white font-medium truncate group-hover:text-accent-400 transition-colors">
          {{ keyData.createdUser?.name || 'Unknown User' }}
        </p>
        <div
          v-if="keyData.group"
          class="flex items-center gap-1.5 mt-1"
        >
          <GroupAvatar
            :avatar="keyData.group.avatar"
            :name="keyData.group.name"
            size="xs"
          />
          <span class="text-gray-400 text-xs truncate">{{ keyData.group.name }}</span>
        </div>
        <p
          v-if="keyData.message"
          class="text-gray-400 text-sm truncate mt-1"
        >
          {{ keyData.message }}
        </p>
      </div>
    </div>
  </Link>
</template>
