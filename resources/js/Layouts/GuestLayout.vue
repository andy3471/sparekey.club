<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { login, register, welcome } from '@/routes';
import games from '@/routes/games';
import groupRoutes from '@/routes/groups';
import type { AuthUser } from '@/types/global';
import AppFooter from '@/Components/layout/AppFooter.vue';
import BrandLogo from '@/Components/ui/BrandLogo.vue';
import AppButton from '@/Components/ui/AppButton.vue';

const page = usePage();
const auth = (page.props.auth as AuthUser | undefined) ?? { user: null };
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <nav class="border-b border-white/10 sticky top-0 z-50 bg-dark-950/90 backdrop-blur-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-12">
          <BrandLogo
            :href="auth?.user ? games.index.url() : welcome.url()"
            size="sm"
          />

          <div
            v-if="!auth?.user"
            class="flex items-center gap-2"
          >
            <Link
              :href="login.url()"
              class="btn-ghost"
            >
              Sign in
            </Link>
            <AppButton
              :href="register.url()"
              size="sm"
            >
              Create account
            </AppButton>
          </div>

          <div
            v-else
            class="flex items-center gap-1"
          >
            <Link
              :href="games.index.url()"
              class="nav-chip"
            >
              Games
            </Link>
            <Link
              :href="groupRoutes.index.url()"
              class="nav-chip"
            >
              Groups
            </Link>
          </div>
        </div>
      </div>
    </nav>

    <main class="flex-1">
      <slot />
    </main>

    <AppFooter />
  </div>
</template>
