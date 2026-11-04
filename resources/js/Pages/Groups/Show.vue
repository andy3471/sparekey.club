<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ui/ConfirmModal.vue';
import GroupAvatar from '@/Components/shared/GroupAvatar.vue';
import UserAvatar from '@/Components/shared/UserAvatar.vue';
import groups from '@/routes/groups';
import users from '@/routes/users';
import { GroupData, GroupMemberData } from '@/types/generated';
import {
  Cog6ToothIcon,
  ArrowRightStartOnRectangleIcon,
  ClipboardDocumentIcon,
  ArrowPathIcon,
  UserGroupIcon,
  EyeIcon,
  EyeSlashIcon,
  UserMinusIcon,
  ArrowsRightLeftIcon,
  CheckIcon,
} from '@heroicons/vue/24/outline';

interface Props {
  group: GroupData;
  members: GroupMemberData[];
  isMember: boolean;
}

const props = defineProps<Props>();

const page = usePage();
const activeGroup = computed(() => page.props.activeGroup);
const isActive = computed(() => activeGroup.value?.id === props.group.id);

const showInviteCode = ref(false);
const inviteUrl = computed(() => {
  if (!props.group.invite_code) return '';
  return `${window.location.origin}/invite/${props.group.invite_code}`;
});

const copied = ref(false);
const copyInviteLink = () => {
  void navigator.clipboard.writeText(inviteUrl.value);
  copied.value = true;
  setTimeout(() => { copied.value = false; }, 2000);
};

const joinGroup = () => {
  router.post(groups.join.url(props.group.id));
};

const showLeaveModal = ref(false);
const showRemoveMemberModal = ref(false);
const showRegenerateModal = ref(false);
const memberToRemove = ref<string | null>(null);

const leaveGroup = () => {
  showLeaveModal.value = false;
  router.post(groups.leave.url(props.group.id));
};

const switchToGroup = () => {
  router.post(groups.switch.url(), { group_id: props.group.id });
};

const confirmRemoveMember = (memberId: string) => {
  memberToRemove.value = memberId;
  showRemoveMemberModal.value = true;
};

const removeMember = () => {
  if (memberToRemove.value) {
    showRemoveMemberModal.value = false;
    router.delete(groups.members.remove.url({ group: props.group.id, user: memberToRemove.value }));
    memberToRemove.value = null;
  }
};

const regenerateCode = () => {
  showRegenerateModal.value = false;
  router.post(groups.regenerateInviteCode.url(props.group.id));
};

const roleBadgeClasses: Record<string, string> = {
  owner: 'bg-accent-600/20 text-accent-400',
  admin: 'bg-primary-600/20 text-primary-400',
  member: 'bg-white/5 text-white/45',
};
</script>

<template>
  <AppLayout>
    <Head :title="group.name" />

    <div class="max-w-3xl mx-auto space-y-5">
      <div class="surface p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
          <GroupAvatar
            :avatar="group.avatar"
            :name="group.name"
            size="lg"
          />

          <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
              <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-white break-words">
                    {{ group.name }}
                  </h1>
                  <span
                    class="text-xs px-2 py-0.5 rounded-md font-medium"
                    :class="group.is_public
                      ? 'bg-success/15 text-success'
                      : 'bg-warning/15 text-warning'"
                  >
                    {{ group.is_public ? 'Public' : 'Private' }}
                  </span>
                </div>
                <p
                  v-if="group.description"
                  class="text-sm text-white/60 leading-relaxed"
                >
                  {{ group.description }}
                </p>
                <p class="mt-2 flex items-center gap-1.5 text-xs text-white/45">
                  <UserGroupIcon class="w-3.5 h-3.5" />
                  {{ group.member_count }} {{ group.member_count === 1 ? 'member' : 'members' }}
                </p>
              </div>

              <div class="flex flex-wrap gap-2 shrink-0">
                <template v-if="isMember">
                  <button
                    v-if="!isActive"
                    type="button"
                    class="btn-primary"
                    @click="switchToGroup"
                  >
                    <ArrowsRightLeftIcon class="w-4 h-4" />
                    Switch to Group
                  </button>
                  <span
                    v-else
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-accent-400 rounded-lg border border-accent-600/25 bg-accent-600/10"
                  >
                    <CheckIcon class="w-4 h-4" />
                    Active
                  </span>

                  <Link
                    v-if="group.can?.update"
                    :href="groups.edit.url(group.id)"
                    class="btn-ghost"
                  >
                    <Cog6ToothIcon class="w-4 h-4" />
                    Settings
                  </Link>

                  <button
                    v-if="group.can?.leave"
                    type="button"
                    class="btn-ghost text-danger hover:bg-danger/10"
                    @click="showLeaveModal = true"
                  >
                    <ArrowRightStartOnRectangleIcon class="w-4 h-4" />
                    Leave
                  </button>
                </template>

                <button
                  v-else-if="group.is_public"
                  type="button"
                  class="btn-primary"
                  @click="joinGroup"
                >
                  <UserGroupIcon class="w-4 h-4" />
                  Join Group
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="group.can?.invite"
        class="surface p-5 sm:p-6"
      >
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-xs font-medium uppercase tracking-wider text-white/40">
            Invite Link
          </h2>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 text-xs text-white/45 hover:text-white transition-colors"
            @click="showInviteCode = !showInviteCode"
          >
            <EyeSlashIcon
              v-if="showInviteCode"
              class="w-3.5 h-3.5"
            />
            <EyeIcon
              v-else
              class="w-3.5 h-3.5"
            />
            {{ showInviteCode ? 'Hide' : 'Show' }}
          </button>
        </div>

        <div
          v-if="showInviteCode"
          class="space-y-2"
        >
          <div class="flex items-center gap-2">
            <div class="relative flex-1 min-w-0">
              <input
                type="text"
                :value="inviteUrl"
                readonly
                class="w-full bg-dark-950 border border-white/10 rounded-lg px-3 py-2 pr-10 text-white/70 text-sm font-mono focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan select-all cursor-text"
              >
              <button
                type="button"
                class="absolute right-1.5 top-1/2 -translate-y-1/2 p-1.5 rounded-md text-white/45 hover:text-white hover:bg-white/5 transition-colors"
                :title="copied ? 'Copied!' : 'Copy to clipboard'"
                @click="copyInviteLink"
              >
                <CheckIcon
                  v-if="copied"
                  class="w-4 h-4 text-success"
                />
                <ClipboardDocumentIcon
                  v-else
                  class="w-4 h-4"
                />
              </button>
            </div>
            <button
              type="button"
              class="btn-ghost shrink-0"
              @click="showRegenerateModal = true"
            >
              <ArrowPathIcon class="w-4 h-4" />
              Regenerate
            </button>
          </div>
          <p class="text-xs text-white/40">
            Share this link with anyone you want to invite.
          </p>
        </div>
      </div>

      <div class="surface p-5 sm:p-6">
        <h2 class="text-xs font-medium uppercase tracking-wider text-white/40 mb-3">
          Members
          <span class="normal-case tracking-normal text-white/30">({{ members.length }})</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
          <div
            v-for="member in members"
            :key="member.id"
            class="flex items-center justify-between gap-2 rounded-lg border border-white/10 px-3 py-2.5"
          >
            <Link
              :href="users.show.url(member.id)"
              class="flex items-center gap-3 flex-1 min-w-0"
            >
              <UserAvatar
                :avatar="member.avatar"
                :name="member.name"
                size="md"
                :show-karma="false"
              />
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate hover:text-accent-400 transition-colors">
                  {{ member.name }}
                </p>
                <span
                  class="inline-block text-xs font-medium px-1.5 py-0.5 rounded-md mt-0.5"
                  :class="roleBadgeClasses[member.role] ?? roleBadgeClasses.member"
                >
                  {{ member.role }}
                </span>
              </div>
            </Link>
            <button
              v-if="group.can?.manageMembers && member.role !== 'owner'"
              type="button"
              class="shrink-0 p-1.5 text-white/40 hover:text-danger hover:bg-danger/10 rounded-md transition-colors"
              title="Remove member"
              @click="confirmRemoveMember(member.id)"
            >
              <UserMinusIcon class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal
      :open="showLeaveModal"
      title="Leave group"
      message="You'll lose access to every key shared in this group. Rejoining later may require a new invite."
      confirm-label="Leave group"
      variant="danger"
      @confirm="leaveGroup"
      @cancel="showLeaveModal = false"
    />

    <ConfirmModal
      :open="showRemoveMemberModal"
      title="Remove member"
      message="Are you sure you want to remove this member from the group?"
      confirm-label="Remove"
      variant="danger"
      @confirm="removeMember"
      @cancel="showRemoveMemberModal = false"
    />

    <ConfirmModal
      :open="showRegenerateModal"
      title="Regenerate invite link"
      message="The current invite URL will stop working immediately. Anyone with the old link will need the new one."
      confirm-label="Regenerate"
      variant="warning"
      @confirm="regenerateCode"
      @cancel="showRegenerateModal = false"
    />
  </AppLayout>
</template>
