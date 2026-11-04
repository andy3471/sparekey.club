<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import GameBanner from '@/Components/shared/GameBanner.vue';
import ParentGameLink from '@/Components/shared/ParentGameLink.vue';
import KeyFeedbackSection from '@/Components/keys/KeyFeedbackSection.vue';
import SharedByCard from '@/Components/keys/SharedByCard.vue';
import ConfirmModal from '@/Components/ui/ConfirmModal.vue';
import { GameData, KeyData, KeyFeedback } from '@/types/generated';
import { claim as claimRoute, feedback as feedbackRoute, show as showRoute } from '@/routes/keys';
import { useCountdown } from '@/Composables/useCountdown';
import PlatformIcon from '@/Components/shared/PlatformIcon.vue';
import { InformationCircleIcon, ExclamationTriangleIcon, ClockIcon, ClipboardDocumentIcon, CheckIcon, TrashIcon } from '@heroicons/vue/24/outline';

interface Props {
  keyData: KeyData;
  parentGame?: GameData | null;
}

const props = withDefaults(defineProps<Props>(), {
  parentGame: null,
});
const keyData = computed(() => props.keyData);
const copied = ref(false);

const form = useForm({ id: keyData.value.id });
watch(() => keyData.value.id, (newId) => { form.id = newId; });

const { isExpired: cooldownExpired, formatted: cooldownFormatted } = useCountdown(keyData.value.can?.cooldownEndsAt ?? null);
watch(cooldownExpired, (expired) => {
  if (expired) {
    router.reload({ only: ['keyData'] });
  }
});

const copyKeyCode = async () => {
  if (!keyData.value.key) return;
  try {
    await navigator.clipboard.writeText(keyData.value.key);
    copied.value = true;
    setTimeout(() => { copied.value = false; }, 2000);
  } catch {
    const input = document.getElementById(`keycode-${keyData.value.id}`) as HTMLInputElement | null;
    if (input != null) {
      input.select();
      document.execCommand('copy');
      copied.value = true;
      setTimeout(() => { copied.value = false; }, 2000);
    }
  }
};

const claimKey = () => {
  if (!keyData.value.id) return;
  form.post(claimRoute.url(keyData.value.id), { preserveScroll: true });
};

const submitFeedback = (value: KeyFeedback) => {
  router.post(feedbackRoute.url(keyData.value.id), { feedback: value }, { preserveScroll: true });
};

const showDeleteModal = ref(false);
const deleteKey = () => {
  showDeleteModal.value = false;
  router.delete(showRoute.url(keyData.value.id));
};
</script>

<template>
  <AppLayout v-if="keyData">
    <Head :title="`${keyData.game?.name || 'Key'} - ${keyData.platform?.name || 'Key'}`" />
    <div class="space-y-5">
      <ParentGameLink
        v-if="parentGame"
        :parent-game="parentGame"
      />

      <div
        v-if="keyData.game"
      >
        <GameBanner
          :game="keyData.game"
          :platform-name="keyData.platform?.name"
          :platform-icon="keyData.platform?.icon"
          linkable
        />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 surface p-5 sm:p-6">
          <div class="flex items-center justify-between gap-3 mb-4">
            <h2 class="text-xs font-medium uppercase tracking-wider text-white/40">
              Key Details
            </h2>
            <div
              v-if="keyData.platform?.name"
              class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border border-white/10 bg-white/5"
            >
              <PlatformIcon
                :icon="keyData.platform.icon"
                size="sm"
                class="text-white/70"
              />
              <span class="text-sm font-medium text-white/80">{{ keyData.platform.name }}</span>
            </div>
          </div>

          <form
            class="space-y-4"
            @submit.prevent="claimKey"
          >
            <div v-if="keyData.can?.claim">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Key Code</label>
                <input
                  class="w-full rounded-lg border border-white/10 bg-dark-950 px-3 py-2 text-sm font-mono tracking-wider text-gray-100 opacity-60 cursor-not-allowed"
                  type="text"
                  value="*****-*****-*****-*****"
                  disabled
                >
              </div>
              <button
                type="submit"
                class="w-full mt-4 btn-primary"
              >
                Claim Key
              </button>
              <p class="text-gray-400 text-sm text-center mt-4">
                Click to claim this key
              </p>
            </div>

            <div v-else-if="keyData.can?.claimDeniedReason === 'own_key' || keyData.can?.claimDeniedReason === 'karma_too_low' || keyData.can?.claimDeniedReason === 'cooldown_active' || keyData.can?.claimDeniedReason === 'already_owns_game'">
              <div>
                <label class="block text-sm font-medium text-gray-500 mb-1.5">Key Code</label>
                <input
                  class="w-full rounded-lg border border-white/10 bg-dark-950/50 px-3 py-2 text-sm font-mono tracking-wider text-gray-500 opacity-40 cursor-not-allowed"
                  type="text"
                  value="*****-*****-*****-*****"
                  disabled
                >
              </div>

              <div
                v-if="keyData.can?.claimDeniedReason === 'own_key'"
                class="bg-primary-600/10 border border-primary-600/30 rounded-lg p-4 mt-4"
              >
                <div class="flex items-start gap-3">
                  <InformationCircleIcon class="w-5 h-5 text-primary-400 flex-shrink-0 mt-0.5" />
                  <div>
                    <p class="text-primary-300 font-medium text-sm">
                      This is your key
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                      You shared this key, so you can't claim your own.
                    </p>
                  </div>
                </div>
              </div>

              <button
                v-if="keyData.can?.delete"
                type="button"
                class="btn-danger w-full mt-4"
                @click="showDeleteModal = true"
              >
                <TrashIcon class="w-5 h-5" />
                Delete Key
              </button>

              <div
                v-else-if="keyData.can?.claimDeniedReason === 'karma_too_low'"
                class="bg-warning/10 border border-warning/30 rounded-lg p-4 mt-4"
              >
                <div class="flex items-start gap-3">
                  <ExclamationTriangleIcon class="w-5 h-5 text-warning flex-shrink-0 mt-0.5" />
                  <div>
                    <p class="text-warning font-medium text-sm">
                      Karma too low
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                      This group requires a minimum karma of <span class="text-white font-semibold">{{ keyData.group?.min_karma }}</span> to claim keys. Share keys and get positive feedback to increase your karma.
                    </p>
                  </div>
                </div>
              </div>

              <div
                v-else-if="keyData.can?.claimDeniedReason === 'cooldown_active'"
                class="bg-primary-600/10 border border-primary-600/30 rounded-lg p-4 mt-4"
              >
                <div class="flex items-start gap-3">
                  <ClockIcon class="w-5 h-5 text-primary-400 flex-shrink-0 mt-0.5" />
                  <div>
                    <p class="text-primary-300 font-medium text-sm">
                      Claim cooldown active
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                      You can claim another key in <span class="text-white font-semibold tabular-nums">{{ cooldownFormatted }}</span>
                    </p>
                  </div>
                </div>
              </div>

              <div
                v-else-if="keyData.can?.claimDeniedReason === 'already_owns_game'"
                class="bg-primary-600/10 border border-primary-600/30 rounded-lg p-4 mt-4"
              >
                <div class="flex items-start gap-3">
                  <InformationCircleIcon class="w-5 h-5 text-primary-400 flex-shrink-0 mt-0.5" />
                  <div>
                    <p class="text-primary-300 font-medium text-sm">
                      You already own this game
                    </p>
                    <p class="text-gray-400 text-sm mt-1">
                      You've already claimed a key for this game. If your key didn't work, mark it as such and you'll be able to claim another.
                    </p>
                    <Link
                      v-if="keyData.can?.ownedKeyId"
                      :href="showRoute.url(keyData.can.ownedKeyId)"
                      class="inline-flex items-center gap-1 text-accent-400 hover:text-accent-300 text-sm font-medium mt-2 transition-colors"
                    >
                      View your claimed key &rarr;
                    </Link>
                  </div>
                </div>
              </div>
            </div>

            <div v-else-if="keyData.key">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Your Key Code</label>
                <div class="relative">
                  <input
                    :id="`keycode-${keyData.id}`"
                    readonly
                    class="w-full rounded-lg border border-white/10 bg-dark-950 px-3 py-2 pr-11 text-sm font-mono tracking-wider text-gray-100 cursor-text select-all focus:outline-none focus:border-cyan focus:ring-1 focus:ring-cyan"
                    type="text"
                    :value="keyData.key"
                  >
                  <button
                    type="button"
                    class="absolute right-1.5 top-1/2 -translate-y-1/2 p-1.5 rounded-md text-white/45 hover:text-white hover:bg-white/5 transition-colors"
                    :title="copied ? 'Copied!' : 'Copy to clipboard'"
                    @click="copyKeyCode"
                  >
                    <CheckIcon
                      v-if="copied"
                      class="w-5 h-5 text-green-400"
                    />
                    <ClipboardDocumentIcon
                      v-else
                      class="w-5 h-5"
                    />
                  </button>
                </div>
              </div>
              <a
                v-if="keyData.platform?.name === 'Steam'"
                :href="`https://store.steampowered.com/account/registerkey?key=${keyData.key}`"
                target="_blank"
                class="btn-secondary w-full mt-4 text-center"
              >
                Redeem on Steam
              </a>

              <KeyFeedbackSection
                :feedback="keyData.feedback ?? null"
                :can-feedback="!!keyData.can?.feedback"
                @submit="submitFeedback"
              />
            </div>

            <div v-else>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Key Code</label>
                <input
                  class="w-full rounded-lg border border-white/10 bg-dark-950 px-3 py-2 text-sm font-mono tracking-wider text-gray-100 opacity-60 cursor-not-allowed"
                  type="text"
                  value="*****-*****-*****-*****"
                  disabled
                >
              </div>
              <button
                type="submit"
                class="btn-primary w-full mt-4"
                disabled
              >
                Claim Key
              </button>
              <p class="text-gray-400 text-sm text-center mt-4">
                This key has already been claimed
              </p>
            </div>
          </form>
        </div>

        <SharedByCard
          v-if="keyData.createdUser"
          :user="keyData.createdUser"
          :group="keyData.group"
        />
      </div>

      <div
        v-if="keyData.message"
        class="surface p-5 sm:p-6"
      >
        <h2 class="text-xs font-medium uppercase tracking-wider text-white/40 mb-2">
          Message from Sharer
        </h2>
        <p class="text-sm text-white/70 leading-relaxed">
          {{ keyData.message }}
        </p>
      </div>
    </div>

    <ConfirmModal
      :open="showDeleteModal"
      title="Delete key"
      message="This permanently deletes the key code. Anyone who hasn't claimed it yet will lose access. This cannot be undone."
      confirm-label="Delete key"
      variant="danger"
      @confirm="deleteKey"
      @cancel="showDeleteModal = false"
    />
  </AppLayout>

  <AppLayout
    v-else
    title="Loading..."
  >
    <div>
      <div class="surface p-8 text-center">
        <div class="inline-flex flex-col items-center gap-3">
          <div class="w-8 h-8 border-2 border-accent-500 border-t-transparent rounded-full animate-spin" />
          <p class="text-gray-400">
            Loading key...
          </p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
