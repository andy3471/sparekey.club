<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import FormInput from '@/Components/ui/FormInput.vue';
import LoadingSpinner from '@/Components/ui/LoadingSpinner.vue';
import SocialLoginButtons from '@/Components/auth/SocialLoginButtons.vue';
import type { SocialProvider } from '@/Components/auth/SocialLoginButtons.vue';
import PendingInviteBanner from '@/Components/onboarding/PendingInviteBanner.vue';
import type { PendingGroupData } from '@/types/generated';
import { login as loginRoute, register } from '@/routes';
import password from '@/routes/password';

interface Props {
  providers: SocialProvider[];
  pendingGroup: PendingGroupData | null;
}

defineProps<Props>();

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(loginRoute.url(), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <Head title="Sign In" />

  <AuthLayout>
    <PendingInviteBanner
      v-if="pendingGroup"
      :group="pendingGroup"
    />

    <h2 class="text-xl font-semibold text-white mb-6">
      Sign in
    </h2>

    <SocialLoginButtons
      v-if="providers.length"
      :providers="providers"
    />

    <div
      v-if="providers.length"
      class="relative my-6"
    >
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-white/10" />
      </div>
      <div class="relative flex justify-center text-sm">
        <span class="bg-dark-900 px-3 text-gray-500">or continue with email</span>
      </div>
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
        placeholder="Enter your password"
        autocomplete="current-password"
        required
      />

      <div class="flex items-center justify-between">
        <label class="flex items-center cursor-pointer">
          <input
            v-model="form.remember"
            type="checkbox"
            class="w-4 h-4 rounded bg-dark-950 border-white/10 text-accent-600 focus:ring-cyan"
          >
          <span class="ml-2 text-sm text-gray-400">Remember me</span>
        </label>
        <Link
          :href="password.request.url()"
          class="text-sm text-accent-400 hover:text-accent-300 font-medium"
        >
          Forgot password?
        </Link>
      </div>

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
          Signing in...
        </span>
        <span v-else>Sign In</span>
      </button>
    </form>

    <p class="text-center text-gray-400 text-sm mt-6">
      Don't have an account?
      <Link
        :href="register.url()"
        class="text-accent-400 hover:text-accent-300 font-medium"
      >
        Create one
      </Link>
    </p>
  </AuthLayout>
</template>
