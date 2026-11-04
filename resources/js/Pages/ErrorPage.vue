<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import BrandLogo from '@/Components/ui/BrandLogo.vue';
import AppFooter from '@/Components/layout/AppFooter.vue';
import games from '@/routes/games';
import { welcome } from '@/routes';
import type { AuthUser } from '@/types/global';

interface Props {
  status: number;
}

const props = defineProps<Props>();
const page = usePage();
const auth = (page.props.auth as AuthUser | undefined) ?? { user: null };

const goBack = () => {
  window.history.back();
};

const homeHref = computed(() => (auth.user ? games.index.url() : welcome.url()));

const title = computed(() => {
  return (
    {
      503: 'Service Unavailable',
      500: 'Server Error',
      404: 'Page Not Found',
      403: 'Forbidden',
    }[props.status] ?? 'Error'
  );
});

const description = computed(() => {
  return (
    {
      503: 'We\'re doing some maintenance. Please check back soon.',
      500: 'Something went wrong on our end. Please try again later.',
      404: 'The page you\'re looking for doesn\'t exist or has been moved.',
      403: 'You don\'t have permission to access this page.',
    }[props.status] ?? 'An unexpected error occurred.'
  );
});

const icon = computed(() => {
  if (props.status === 404) {
    return 'search';
  }
  if (props.status === 403) {
    return 'lock';
  }
  return 'alert';
});
</script>

<template>
  <Head :title="`${status} - ${title}`" />

  <div class="min-h-screen flex flex-col">
    <div class="flex flex-1 flex-col items-center justify-center px-4 py-12">
      <div class="w-full max-w-md text-center">
        <div class="mb-8">
          <BrandLogo
            :href="homeHref"
            size="md"
          />
        </div>

        <div class="mb-6">
          <svg
            v-if="icon === 'search'"
            class="w-12 h-12 mx-auto text-white/25"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.5"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
            />
          </svg>
          <svg
            v-else-if="icon === 'lock'"
            class="w-12 h-12 mx-auto text-white/25"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.5"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
            />
          </svg>
          <svg
            v-else
            class="w-12 h-12 mx-auto text-white/25"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="1.5"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
            />
          </svg>
        </div>

        <p class="text-5xl font-semibold text-white/20 mb-2">
          {{ status }}
        </p>

        <h1 class="text-xl font-semibold text-white mb-2">
          {{ title }}
        </h1>

        <p class="text-sm text-white/55 mb-8">
          {{ description }}
        </p>

        <div class="flex items-center justify-center gap-2.5">
          <Link
            :href="homeHref"
            class="btn-primary text-sm"
          >
            Go Home
          </Link>
          <button
            type="button"
            class="btn-secondary text-sm"
            @click="goBack"
          >
            Go Back
          </button>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>
