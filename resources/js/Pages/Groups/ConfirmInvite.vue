<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import GroupAvatar from '@/Components/shared/GroupAvatar.vue';
import { GroupData } from '@/types/generated';
import groups from '@/routes/groups';

interface Props {
  group: GroupData;
  code: string;
}

const props = defineProps<Props>();

const acceptInvite = () => {
  router.post(groups.joinViaCode.accept.url(props.code));
};

const declineInvite = () => {
  router.visit(groups.index.url());
};
</script>

<template>
  <AppLayout title="Join Group">
    <Head title="Join Group" />
    <div class="max-w-lg mx-auto px-4 py-12">
      <div class="bg-dark-800 rounded-xl border border-dark-700 p-8 text-center space-y-6">
        <GroupAvatar
          :avatar="group.avatar"
          :name="group.name"
          size="xl"
          class="mx-auto"
        />
        <div>
          <h1 class="text-2xl font-display font-bold text-white mb-2">
            Join {{ group.name }}?
          </h1>
          <p
            v-if="group.description"
            class="text-gray-400"
          >
            {{ group.description }}
          </p>
          <p
            v-else
            class="text-gray-400"
          >
            You have been invited to join this group.
          </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
          <button
            type="button"
            class="px-6 py-2.5 rounded-lg bg-primary-600 text-white font-medium hover:bg-primary-500 transition"
            @click="acceptInvite"
          >
            Join Group
          </button>
          <button
            type="button"
            class="px-6 py-2.5 rounded-lg bg-dark-700 text-gray-300 font-medium hover:bg-dark-600 transition"
            @click="declineInvite"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
