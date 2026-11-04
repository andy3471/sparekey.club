<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import FormInput from '@/Components/ui/FormInput.vue';
import LoadingSpinner from '@/Components/ui/LoadingSpinner.vue';
import PendingInviteBanner from '@/Components/onboarding/PendingInviteBanner.vue';
import type { PendingGroupData } from '@/types/generated';
import credentials from '@/routes/onboarding/credentials';

interface Props {
  name: string;
  pendingGroup: PendingGroupData | null;
}

defineProps<Props>();

const form = useForm({
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(credentials.store.url(), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <Head title="Complete Your Account" />

  <AuthLayout>
    <PendingInviteBanner
      v-if="pendingGroup"
      :group="pendingGroup"
    />

    <div class="text-center mb-6">
      <div
        v-if="!pendingGroup"
        class="inline-flex items-center gap-2 text-xs font-medium text-gray-500 mb-4"
      >
        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-accent-600 text-white text-xs">1</span>
        <span class="text-accent-400">Account Setup</span>
        <span class="w-8 h-px bg-dark-600" />
        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-dark-600 text-gray-500 text-xs">2</span>
        <span>Join a Group</span>
      </div>

      <h2 class="text-xl font-semibold text-white mb-1">
        Welcome, {{ name }}!
      </h2>
      <p class="text-gray-400 text-sm">
        Set your email and password to secure your account.
      </p>
    </div>

    <form
      class="space-y-5"
      @submit.prevent="submit"
    >
      <FormInput
        id="email"
        v-model="form.email"
        label="Email"
        type="email"
        :error="form.errors.email"
        placeholder="you@example.com"
        autocomplete="email"
        required
      />

      <FormInput
        id="password"
        v-model="form.password"
        label="Password"
        type="password"
        :error="form.errors.password"
        placeholder="Create a strong password"
        autocomplete="new-password"
        required
      />

      <FormInput
        id="password_confirmation"
        v-model="form.password_confirmation"
        label="Confirm Password"
        type="password"
        :error="form.errors.password_confirmation"
        placeholder="Confirm your password"
        autocomplete="new-password"
        required
      />

      <button
        type="submit"
        :disabled="form.processing"
        class="w-full btn-primary"
      >
        <span
          v-if="form.processing"
          class="flex items-center justify-center"
        >
          <LoadingSpinner
            size="sm"
            class="-ml-1 mr-2"
          />
          Saving...
        </span>
        <span v-else>Continue</span>
      </button>
    </form>
  </AuthLayout>
</template>
