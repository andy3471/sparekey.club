<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import groups from '@/routes/groups';
import onboardingGroup from '@/routes/onboarding/group';

interface PublicGroup {
  id: string;
  name: string;
  slug: string;
  description: string | null;
  member_count: number;
  avatar: string | null;
}

interface Props {
  publicGroups: PublicGroup[];
  joinedGroupCount: number;
}

const props = defineProps<Props>();

const joinGroup = (groupId: string) => {
  router.post(groups.join.url(groupId), {}, {
    preserveScroll: true,
  });
};

const finish = () => {
  router.post(onboardingGroup.skip.url());
};
</script>

<template>
  <Head title="Join a Group" />

  <AuthLayout>
    <div class="text-center mb-6">
      <div class="inline-flex items-center gap-2 text-xs font-medium text-gray-500 mb-4">
        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-accent-600 text-white text-xs">
          <svg
            class="w-3 h-3"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
              clip-rule="evenodd"
            />
          </svg>
        </span>
        <span class="text-accent-400">Account Setup</span>
        <span class="w-8 h-px bg-dark-600" />
        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-accent-600 text-white text-xs">2</span>
        <span class="text-accent-400">Join a Group</span>
      </div>

      <h2 class="text-xl font-semibold text-white mb-1">
        Join a Group
      </h2>
      <p class="text-gray-400 text-sm">
        Groups are where game keys are shared. Join as many as you like, or create your own.
      </p>
    </div>

    <div
      v-if="publicGroups.length"
      class="space-y-2 max-h-64 overflow-y-auto pr-1 -mr-1"
    >
      <div
        v-for="group in publicGroups"
        :key="group.id"
        class="flex items-center justify-between p-3 rounded-lg border border-white/10 hover:border-white/20 transition-colors"
      >
        <div class="flex items-center gap-3 min-w-0">
          <div class="w-8 h-8 rounded-lg bg-dark-600 flex items-center justify-center flex-shrink-0 overflow-hidden">
            <img
              v-if="group.avatar"
              :src="group.avatar"
              :alt="group.name"
              class="w-full h-full object-cover"
            >
            <span
              v-else
              class="text-xs font-bold text-gray-400"
            >{{ group.name.charAt(0).toUpperCase() }}</span>
          </div>
          <div class="min-w-0">
            <p class="text-sm font-medium text-white truncate">
              {{ group.name }}
            </p>
            <p class="text-xs text-gray-500">
              {{ group.member_count }} {{ group.member_count === 1 ? 'member' : 'members' }}
            </p>
          </div>
        </div>
        <button
          class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-md bg-accent-600 hover:bg-accent-700 text-white transition-colors"
          @click="joinGroup(group.id)"
        >
          Join
        </button>
      </div>
    </div>

    <div
      v-else-if="props.joinedGroupCount === 0"
      class="text-center py-6"
    >
      <p class="text-gray-500 text-sm">
        No public groups available yet.
      </p>
    </div>

    <div
      v-else
      class="text-center py-6"
    >
      <p class="text-gray-400 text-sm">
        You've joined all available public groups!
      </p>
    </div>

    <div class="mt-6 space-y-3">
      <button
        v-if="props.joinedGroupCount > 0"
        class="w-full btn-primary"
        @click="finish"
      >
        Continue
      </button>

      <Link
        :href="groups.create.url()"
        class="btn-secondary block w-full text-center"
      >
        Create a Group
      </Link>

      <button
        v-if="props.joinedGroupCount === 0"
        class="w-full text-center text-gray-500 hover:text-gray-400 text-sm py-2 transition-colors"
        @click="finish"
      >
        Skip for now
      </button>
    </div>
  </AuthLayout>
</template>
