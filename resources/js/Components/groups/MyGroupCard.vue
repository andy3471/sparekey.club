<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import GroupAvatar from '@/Components/shared/GroupAvatar.vue';
import groups from '@/routes/groups';
import { GroupData } from '@/types/generated';
import { UsersIcon, GlobeAltIcon, LockClosedIcon } from '@heroicons/vue/24/outline';

interface Props {
  group: GroupData;
  isActive: boolean;
}

defineProps<Props>();

const emit = defineEmits<{
  switchTo: [groupId: string, event: Event];
}>();
</script>

<template>
  <Link
    :href="groups.show.url(group.id)"
    class="surface p-5 transition-colors group relative block"
    :class="isActive
      ? 'border-accent-500'
      : 'hover:border-white/20'"
  >
    <div
      v-if="isActive"
      class="absolute top-3 right-3"
    >
      <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-md bg-accent-600/15 text-accent-400 font-medium">
        Active
      </span>
    </div>

    <div class="flex items-start gap-3 mb-3 pr-16">
      <GroupAvatar
        :avatar="group.avatar"
        :name="group.name"
        size="md"
      />
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
          <h3
            class="text-lg font-semibold transition-colors truncate"
            :class="isActive ? 'text-accent-400' : 'text-white group-hover:text-accent-400'"
          >
            {{ group.name }}
          </h3>
          <span
            v-if="group.role"
            class="text-xs px-2 py-1 rounded-md font-medium flex-shrink-0"
            :class="{
              'bg-accent-600/15 text-accent-400': group.role === 'owner',
              'bg-primary-600/15 text-primary-400': group.role === 'admin',
              'bg-white/5 text-gray-400': group.role === 'member',
            }"
          >
            {{ group.role }}
          </span>
        </div>
      </div>
    </div>

    <p
      v-if="group.description"
      class="text-gray-400 text-sm mb-3 line-clamp-2"
    >
      {{ group.description }}
    </p>

    <div class="flex items-center justify-between mt-auto">
      <div class="flex items-center gap-3 text-xs text-gray-500">
        <span class="flex items-center gap-1">
          <UsersIcon class="w-3.5 h-3.5" />
          {{ group.member_count }} {{ group.member_count === 1 ? 'member' : 'members' }}
        </span>
        <span
          v-if="group.is_public"
          class="flex items-center gap-1 text-green-400"
        >
          <GlobeAltIcon class="w-3.5 h-3.5" />
          Public
        </span>
        <span
          v-else
          class="flex items-center gap-1"
        >
          <LockClosedIcon class="w-3.5 h-3.5" />
          Private
        </span>
      </div>
      <button
        v-if="!isActive"
        class="text-xs px-3 py-1.5 rounded-lg border border-white/10 bg-transparent hover:bg-accent-600 hover:border-accent-600 text-gray-400 hover:text-white font-medium transition-colors"
        @click="emit('switchTo', group.id, $event)"
      >
        Set Active
      </button>
    </div>
  </Link>
</template>
