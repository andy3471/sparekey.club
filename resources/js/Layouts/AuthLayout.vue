<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppFooter from '@/Components/layout/AppFooter.vue';
import FlashMessages from '@/Components/layout/FlashMessages.vue';
import BrandLogo from '@/Components/ui/BrandLogo.vue';
import { welcome } from '@/routes';
import games from '@/routes/games';
import type { AuthUser } from '@/types/global';

const page = usePage();
const auth = (page.props.auth as AuthUser | undefined) ?? { user: null };
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
      <div class="w-full max-w-md">
        <div class="text-center mb-6">
          <BrandLogo
            :href="auth?.user ? games.index.url() : welcome.url()"
            size="md"
          />
        </div>

        <div class="surface p-5 sm:p-6">
          <slot />
        </div>
      </div>
    </div>

    <FlashMessages />
    <AppFooter />
  </div>
</template>
