<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import NavSearchBar from './NavSearchBar.vue';
import GroupSwitcher from './GroupSwitcher.vue';
import UserAvatar from '@/Components/shared/UserAvatar.vue';
import { logout as logoutRoute } from '@/routes';
import games from '@/routes/games';
import keys from '@/routes/keys';
import users from '@/routes/users';
import groupRoutes from '@/routes/groups';
import { dashboard } from '@/routes/filament/admin/pages';
import { UserData } from '@/types/generated';
import type { ComboboxItem } from '@/types/ui';

interface Props {
  user: UserData;
}

defineProps<Props>();

const emit = defineEmits<{
  close: [];
  select: [item: ComboboxItem];
  search: [query: string];
}>();

const logout = () => {
  const form = useForm({});
  form.post(logoutRoute.url());
};

const handleSelect = (item: ComboboxItem) => {
  emit('close');
  emit('select', item);
};

const handleSearch = (query: string) => {
  emit('close');
  emit('search', query);
};
</script>

<template>
  <div class="lg:hidden border-t border-white/10">
    <div class="px-4 py-4 space-y-1">
      <div class="mb-3">
        <NavSearchBar
          id="mobile-search"
          input-class="w-full pl-9 pr-3 py-1.5 bg-dark-950 border border-white/10 text-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan focus:border-cyan transition-colors placeholder-gray-500"
          @select="handleSelect"
          @search="handleSearch"
        />
      </div>

      <Link
        :href="games.index.url()"
        class="block nav-chip"
        @click="emit('close')"
      >
        Games
      </Link>
      <Link
        :href="groupRoutes.index.url()"
        class="block nav-chip"
        @click="emit('close')"
      >
        Groups
      </Link>
      <Link
        :href="keys.create.url()"
        class="block nav-chip"
        @click="emit('close')"
      >
        Add Key
      </Link>

      <div class="py-2">
        <GroupSwitcher />
      </div>

      <div class="border-t border-white/10 my-2" />

      <div class="flex items-center px-3 py-2">
        <UserAvatar
          :avatar="user.avatar"
          :name="user.name"
          :karma="user.karma"
          :karma-colour="user.karma_colour"
          size="md"
          tooltip-position="below"
          class="mr-3"
        />
        <span class="text-white font-medium text-sm truncate">{{ user.name }}</span>
      </div>

      <Link
        :href="users.show.url(user.id)"
        class="block nav-chip"
        @click="emit('close')"
      >
        View Profile
      </Link>
      <Link
        :href="users.edit.url(user.id)"
        class="block nav-chip"
        @click="emit('close')"
      >
        Update Profile
      </Link>
      <Link
        v-if="user.is_admin"
        :href="dashboard.url()"
        class="block nav-chip"
        @click="emit('close')"
      >
        Admin
      </Link>
      <Link
        :href="keys.claimed.index.url()"
        class="block nav-chip"
        @click="emit('close')"
      >
        Claimed Keys
      </Link>
      <Link
        :href="keys.shared.index.url()"
        class="block nav-chip"
        @click="emit('close')"
      >
        Shared Keys
      </Link>

      <div class="border-t border-white/10 my-2" />

      <button
        type="button"
        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium text-danger hover:bg-danger-600/20 transition-colors"
        @click="logout"
      >
        Logout
      </button>
    </div>
  </div>
</template>
