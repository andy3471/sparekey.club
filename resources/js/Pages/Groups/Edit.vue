<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import FormInput from '@/Components/ui/FormInput.vue';
import FormTextarea from '@/Components/ui/FormTextarea.vue';
import AppSwitch from '@/Components/ui/AppSwitch.vue';
import AvatarUpload from '@/Components/shared/AvatarUpload.vue';
import DiscordWebhookSection from '@/Components/groups/DiscordWebhookSection.vue';
import KarmaRequirementSection from '@/Components/groups/KarmaRequirementSection.vue';
import ClaimCooldownSection from '@/Components/groups/ClaimCooldownSection.vue';
import DangerZone from '@/Components/groups/DangerZone.vue';
import { update as updateRoute, destroy as destroyRoute } from '@/routes/groups';
import { GroupData } from '@/types/generated';

interface Props {
  group: GroupData;
}

const props = defineProps<Props>();

const form = useForm({
  _method: 'PUT',
  name: props.group.name,
  description: props.group.description ?? '',
  is_public: props.group.is_public ?? false,
  avatar: null as File | null,
  discord_webhook_url: props.group.discord_webhook_url ?? '',
  min_karma: props.group.min_karma,
  claim_cooldown_minutes: props.group.claim_cooldown_minutes,
});

const initials = computed(() => form.name ? form.name.charAt(0).toUpperCase() : '?');

const handleAvatarChange = (file: File) => {
  form.avatar = file;
};

const submit = () => {
  form.post(updateRoute.url(props.group.id), { forceFormData: true });
};

const deleteGroup = () => {
  router.delete(destroyRoute.url(props.group.id));
};
</script>

<template>
  <AppLayout title="Edit Group">
    <Head :title="`Edit ${group.name}`" />
    <div class="max-w-2xl mx-auto space-y-5">
      <div class="surface p-5 sm:p-6">
        <form
          class="space-y-5"
          @submit.prevent="submit"
        >
          <AvatarUpload
            :current-image="group.avatar"
            :initials="initials"
            :error="form.errors.avatar"
            label="Group Avatar"
            @update:file="handleAvatarChange"
          />

          <FormInput
            id="name"
            v-model="form.name"
            label="Group Name"
            :error="form.errors.name"
            :maxlength="50"
            required
          />

          <FormTextarea
            id="description"
            v-model="form.description"
            label="Description"
            :error="form.errors.description"
            :maxlength="1000"
          >
            <template #label-suffix>
              <span class="text-gray-500"> (optional)</span>
            </template>
          </FormTextarea>

          <AppSwitch
            v-model="form.is_public"
            label="Public Group"
            description="Anyone can find and join a public group"
          />

          <DiscordWebhookSection
            v-model="form.discord_webhook_url"
            :error="form.errors.discord_webhook_url"
          />

          <KarmaRequirementSection
            :model-value="form.min_karma"
            :error="form.errors.min_karma"
            @update:model-value="form.min_karma = Number($event)"
          />

          <ClaimCooldownSection
            :model-value="form.claim_cooldown_minutes"
            :error="form.errors.claim_cooldown_minutes"
            @update:model-value="form.claim_cooldown_minutes = $event ? Number($event) : null"
          />

          <div class="flex justify-end pt-1">
            <button
              type="submit"
              :disabled="form.processing"
              class="btn-primary"
            >
              Save Changes
            </button>
          </div>
        </form>
      </div>

      <DangerZone
        v-if="group.can?.delete"
        @delete="deleteGroup"
      />
    </div>
  </AppLayout>
</template>
