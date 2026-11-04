<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import UserAvatar from '@/Components/shared/UserAvatar.vue';
import GroupAvatar from '@/Components/shared/GroupAvatar.vue';
import { UserData, PublicUserData, GroupData } from '@/types/generated';
import type { AuthUser } from '@/types/global';
import users from '@/routes/users';
import keys from '@/routes/keys';
import groupRoutes from '@/routes/groups';
import {
  PencilSquareIcon,
  CheckBadgeIcon,
  ShareIcon,
} from '@heroicons/vue/24/outline';

interface Props {
  user: UserData | PublicUserData;
  groups: GroupData[];
}

const props = defineProps<Props>();

const page = usePage();
const auth = (page.props.auth as AuthUser | undefined) ?? { user: null };
const isOwnProfile = auth.user?.id === props.user.id;
</script>

<template>
  <AppLayout>
    <Head :title="user.name" />

    <div class="max-w-3xl mx-auto space-y-5">
      <div class="surface p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
          <UserAvatar
            :avatar="user.avatar"
            :name="user.name"
            :karma="user.karma"
            :karma-colour="user.karma_colour"
            size="lg"
          />

          <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
              <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-white break-words">
                  {{ user.name }}
                </h1>
                <p
                  v-if="user.bio"
                  class="mt-1.5 text-sm text-white/60 leading-relaxed"
                >
                  {{ user.bio }}
                </p>
                <p
                  v-else
                  class="mt-1.5 text-sm text-white/40"
                >
                  No bio yet
                </p>
              </div>

              <Link
                v-if="isOwnProfile"
                :href="users.edit.url(user.id)"
                class="btn-primary shrink-0 self-start"
              >
                <PencilSquareIcon class="w-4 h-4" />
                Edit Profile
              </Link>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="isOwnProfile"
        class="grid grid-cols-1 sm:grid-cols-2 gap-3"
      >
        <Link
          :href="keys.claimed.index.url()"
          class="surface p-4 hover:border-white/20 transition-colors"
        >
          <div class="flex items-center justify-between gap-3">
            <div>
              <h2 class="text-sm font-medium text-white">
                Claimed Keys
              </h2>
              <p class="text-xs text-white/45 mt-0.5">
                Keys you’ve claimed
              </p>
            </div>
            <CheckBadgeIcon class="w-5 h-5 text-accent-400 shrink-0" />
          </div>
        </Link>

        <Link
          :href="keys.shared.index.url()"
          class="surface p-4 hover:border-white/20 transition-colors"
        >
          <div class="flex items-center justify-between gap-3">
            <div>
              <h2 class="text-sm font-medium text-white">
                Shared Keys
              </h2>
              <p class="text-xs text-white/45 mt-0.5">
                Keys you’ve shared
              </p>
            </div>
            <ShareIcon class="w-5 h-5 text-accent-400 shrink-0" />
          </div>
        </Link>
      </div>

      <div
        v-if="groups.length > 0"
        class="surface p-5 sm:p-6"
      >
        <h2 class="text-xs font-medium uppercase tracking-wider text-white/40 mb-3">
          Groups
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
          <Link
            v-for="group in groups"
            :key="group.id"
            :href="groupRoutes.show.url(group.id)"
            class="flex items-center gap-3 rounded-lg border border-white/10 px-3 py-2.5 hover:border-white/20 hover:bg-white/[0.03] transition-colors"
          >
            <GroupAvatar
              :avatar="group.avatar"
              :name="group.name"
              size="md"
            />
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-white truncate">
                {{ group.name }}
              </p>
              <p class="text-xs text-white/45">
                {{ group.member_count }} {{ group.member_count === 1 ? 'member' : 'members' }}
                <span class="text-white/30"> · </span>
                {{ group.is_public ? 'Public' : 'Private' }}
              </p>
            </div>
          </Link>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
