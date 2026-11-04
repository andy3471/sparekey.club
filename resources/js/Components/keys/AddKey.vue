<script setup lang="ts">
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppCombobox from '@/Components/ui/AppCombobox.vue';
import AppListbox from '@/Components/ui/AppListbox.vue';
import FormInput from '@/Components/ui/FormInput.vue';
import FormTextarea from '@/Components/ui/FormTextarea.vue';
import LoadingSpinner from '@/Components/ui/LoadingSpinner.vue';
import { AutocompleteGameData, GroupData, PlatformData } from '@/types/generated';
import keys from '@/routes/keys';
import PlatformIcon from '@/Components/shared/PlatformIcon.vue';
import { PlusIcon } from '@heroicons/vue/24/outline';
import type { ComboboxItem } from '@/types/ui';
import { autocomplete } from '@/routes';

interface Props {
  platforms: PlatformData[];
  game?: AutocompleteGameData | null;
  groups: GroupData[];
  activeGroupId?: string | null;
}

const props = defineProps<Props>();

const selectedGame = ref<ComboboxItem | null>(props.game ? { id: props.game.id, name: props.game.name } : null);

const form = useForm({
  igdb_id: props.game?.id ?? '',
  platform_id: '',
  key: '',
  message: '',
  group_id: props.activeGroupId ?? '',
});

const groupOptions = props.groups.map(g => ({ id: g.id, name: g.name }));
const platformOptions = props.platforms.map(p => ({ id: p.id, name: p.name, icon: p.icon }));

const currentStep = computed(() => {
  if (!form.group_id) {
    return 'group';
  }
  if (!selectedGame.value || !form.platform_id) {
    return 'game';
  }
  return 'key';
});

const submit = () => {
  form.igdb_id = selectedGame.value?.id.toString() ?? '';

  if (!form.group_id) {
    form.setError('group_id', 'Please select a group to share with.');
    return;
  }

  if (!form.platform_id) {
    form.setError('platform_id', 'Please select a platform.');
    return;
  }

  form.post(keys.store.url(), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      selectedGame.value = null;
    },
  });
};

const handleGameSelect = (item: ComboboxItem | null) => {
  selectedGame.value = item;
};
</script>

<template>
  <div class="surface p-5 sm:p-6 max-sm:pb-24">
    <p class="text-sm text-white/55 mb-3">
      Paste a spare key for a group you’re in.
    </p>

    <p
      class="text-xs text-white/40 mb-5"
      aria-label="Form progress"
    >
      <span :class="currentStep === 'group' ? 'text-accent-400' : 'text-white/55'">Group</span>
      <span class="mx-1.5 text-white/20">→</span>
      <span :class="currentStep === 'game' ? 'text-accent-400' : 'text-white/55'">Game</span>
      <span class="mx-1.5 text-white/20">→</span>
      <span :class="currentStep === 'key' ? 'text-accent-400' : 'text-white/55'">Key</span>
    </p>

    <form
      class="space-y-6"
      @submit.prevent="submit"
    >
      <div
        v-if="Object.keys(form.errors).length > 0"
        class="rounded-lg border border-danger/40 bg-danger/10 p-3"
      >
        <div
          v-for="(error, field) in form.errors"
          :key="field"
          class="text-danger-300 text-sm mb-1 last:mb-0"
        >
          <strong class="capitalize">{{ String(field).replace('_', ' ') }}:</strong> {{ Array.isArray(error) ? error[0] : error }}
        </div>
      </div>

      <section class="space-y-3">
        <h2 class="text-xs font-medium uppercase tracking-wider text-white/40">
          Group
        </h2>
        <AppListbox
          v-model="form.group_id"
          :options="groupOptions"
          label="Share with"
          placeholder="Select a group"
          :error="form.errors.group_id"
          hint="Only members of this group can claim the key"
          required
        />
      </section>

      <section class="space-y-3 border-t border-white/10 pt-5">
        <h2 class="text-xs font-medium uppercase tracking-wider text-white/40">
          Game
        </h2>
        <div class="space-y-3">
          <div>
            <label
              for="igdb_id"
              class="block text-sm font-medium text-gray-300 mb-1.5"
            >
              Game or DLC <span class="text-red-400">*</span>
            </label>
            <AppCombobox
              id="igdb_id"
              :model-value="selectedGame"
              name="igdb_id"
              placeholder="Search for a game or DLC..."
              :url="autocomplete.url()"
              aria-label="Search for a game or DLC"
              @update:model-value="handleGameSelect"
            />
            <p class="mt-1.5 text-xs text-gray-500">
              Start typing to search IGDB
            </p>
            <p
              v-if="form.errors.igdb_id"
              class="mt-1 text-sm text-danger-400"
            >
              {{ form.errors.igdb_id }}
            </p>
          </div>

          <AppListbox
            v-model="form.platform_id"
            :options="platformOptions"
            label="Platform"
            placeholder="Select a platform"
            :error="form.errors.platform_id"
            required
          >
            <template #selected="{ option }">
              <PlatformIcon
                :icon="option.icon ?? 'generic'"
                size="sm"
              />
              {{ option.name }}
            </template>
            <template #option="{ option }">
              <PlatformIcon
                :icon="option.icon ?? 'generic'"
                size="sm"
              />
              {{ option.name }}
            </template>
          </AppListbox>
        </div>
      </section>

      <section class="space-y-3 border-t border-white/10 pt-5">
        <h2 class="text-xs font-medium uppercase tracking-wider text-white/40">
          Key
        </h2>
        <div class="space-y-3">
          <FormInput
            id="key"
            v-model="form.key"
            label="Key code"
            placeholder="XXXXX-XXXXX-XXXXX-XXXXX"
            :error="form.errors.key"
            required
            input-class="font-mono tracking-wider"
          />
          <FormTextarea
            id="message"
            v-model="form.message"
            label="Message"
            placeholder="Optional note for whoever claims this"
            :error="form.errors.message"
            :rows="3"
          >
            <template #label-suffix>
              <span class="text-gray-500 font-normal"> (optional)</span>
            </template>
          </FormTextarea>
        </div>
      </section>

      <div class="hidden sm:block border-t border-white/10 pt-5">
        <button
          type="submit"
          class="btn-primary w-auto"
          :disabled="form.processing"
        >
          <span
            v-if="form.processing"
            class="flex items-center justify-center"
          >
            <LoadingSpinner
              size="sm"
              class="-ml-0.5 mr-2"
            />
            Adding key...
          </span>
          <span
            v-else
            class="flex items-center justify-center"
          >
            <PlusIcon class="w-4 h-4" />
            Add key
          </span>
        </button>
      </div>

      <div class="sm:hidden fixed inset-x-0 bottom-0 z-40 border-t border-white/10 bg-dark-950/95 backdrop-blur-sm p-3 pb-[max(0.75rem,env(safe-area-inset-bottom))]">
        <button
          type="submit"
          class="btn-primary w-full"
          :disabled="form.processing"
        >
          <span
            v-if="form.processing"
            class="flex items-center justify-center"
          >
            <LoadingSpinner
              size="sm"
              class="-ml-0.5 mr-2"
            />
            Adding key...
          </span>
          <span
            v-else
            class="flex items-center justify-center"
          >
            <PlusIcon class="w-4 h-4" />
            Add key
          </span>
        </button>
      </div>
    </form>
  </div>
</template>
