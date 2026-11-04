<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { login } from '@/routes';
import games from '@/routes/games';
import keys from '@/routes/keys';
import groupRoutes from '@/routes/groups';
import NavSearchBar from '@/Components/navbar/NavSearchBar.vue';
import NavUserDropdown from '@/Components/navbar/NavUserDropdown.vue';
import NavMobileMenu from '@/Components/navbar/NavMobileMenu.vue';
import GroupSwitcher from '@/Components/navbar/GroupSwitcher.vue';
import BrandLogo from '@/Components/ui/BrandLogo.vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import type { AuthUser } from '@/types/global';
import type { ComboboxItem } from '@/types/ui';

const page = usePage();
const auth = (page.props.auth as AuthUser | undefined) ?? { user: null };
const mobileMenuOpen = ref(false);

const isActive = (path: string): boolean => {
  const current = page.url.split('?')[0];
  if (path === '/') {
    return current === '/';
  }
  return current === path || current.startsWith(`${path}/`);
};

const handleGameSelect = (item: ComboboxItem) => {
  mobileMenuOpen.value = false;
  router.visit(games.show.url({ igdb_id: item.id }));
};

const handleSearch = (query: string) => {
  mobileMenuOpen.value = false;
  const trimmed = query.trim();
  router.visit(trimmed ? `/search?search=${encodeURIComponent(trimmed)}` : '/search?search=');
};
</script>

<template>
  <nav class="sticky top-0 z-50 border-b border-white/10 bg-dark-950/90 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center h-12">
        <BrandLogo
          :href="games.index.url()"
          size="sm"
        />

        <div
          v-if="auth?.user"
          class="hidden lg:flex items-center gap-0.5 ml-6"
        >
          <Link
            :href="games.index.url()"
            class="nav-chip"
            :class="{ 'nav-chip-active': isActive('/games') || isActive('/search') }"
            prefetch
          >
            Games
          </Link>
          <Link
            :href="groupRoutes.index.url()"
            class="nav-chip"
            :class="{ 'nav-chip-active': isActive('/groups') }"
            prefetch
          >
            Groups
          </Link>
          <Link
            :href="keys.create.url()"
            class="nav-chip"
            :class="{ 'nav-chip-active': isActive('/keys/create') }"
            prefetch
          >
            Add Key
          </Link>
        </div>

        <div class="flex-1" />

        <div
          v-if="auth?.user"
          class="hidden lg:flex items-center gap-3"
        >
          <NavSearchBar
            id="navbar-search"
            input-class="w-48 xl:w-56 pl-9 pr-3 py-1.5 bg-dark-900 border border-white/10 text-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan focus:border-cyan transition-colors placeholder-gray-500"
            @select="handleGameSelect"
            @search="handleSearch"
          />
          <GroupSwitcher />
          <NavUserDropdown :user="auth.user" />
        </div>

        <Link
          v-if="!auth?.user"
          :href="login.url()"
          class="hidden lg:inline-flex nav-chip"
        >
          Sign in
        </Link>

        <button
          v-if="auth?.user"
          type="button"
          class="lg:hidden flex items-center justify-center p-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition-colors ml-3"
          :aria-expanded="mobileMenuOpen"
          :aria-label="mobileMenuOpen ? 'Close menu' : 'Open menu'"
          @click="mobileMenuOpen = !mobileMenuOpen"
        >
          <Bars3Icon
            v-if="!mobileMenuOpen"
            class="w-6 h-6"
          />
          <XMarkIcon
            v-else
            class="w-6 h-6"
          />
        </button>
      </div>
    </div>

    <NavMobileMenu
      v-if="auth?.user && mobileMenuOpen"
      :user="auth.user"
      @close="mobileMenuOpen = false"
      @select="handleGameSelect"
      @search="handleSearch"
    />
  </nav>
</template>
