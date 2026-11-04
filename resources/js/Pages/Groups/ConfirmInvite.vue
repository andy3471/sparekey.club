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

    <div class="max-w-lg mx-auto">
      <div class="surface p-6 sm:p-8 text-center space-y-5">
        <GroupAvatar
          :avatar="group.avatar"
          :name="group.name"
          size="xl"
          class="mx-auto"
        />
        <div>
          <h1 class="text-xl font-semibold text-white mb-1.5">
            Join {{ group.name }}?
          </h1>
          <p class="text-sm text-white/55">
            {{ group.description || 'You have been invited to join this group.' }}
          </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2.5 justify-center">
          <button
            type="button"
            class="btn-primary"
            @click="acceptInvite"
          >
            Join Group
          </button>
          <button
            type="button"
            class="btn-ghost"
            @click="declineInvite"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
