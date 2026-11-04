<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { PlatformData } from '@/types/generated';
import games from '@/routes/games';
import PlatformIcon from '@/Components/shared/PlatformIcon.vue';
import { ChevronDownIcon, XMarkIcon } from '@heroicons/vue/20/solid';

interface Props {
  platforms: PlatformData[];
  selectedPlatforms?: string[];
}

const props = withDefaults(defineProps<Props>(), {
  selectedPlatforms: () => [],
});

const selected = ref<string[]>([...props.selectedPlatforms]);

const togglePlatform = (platformId: string) => {
  const index = selected.value.indexOf(platformId);
  if (index > -1) {
    selected.value.splice(index, 1);
  } else {
    selected.value.push(platformId);
  }
  applyFilters();
};

const removePlatform = (platformId: string) => {
  const index = selected.value.indexOf(platformId);
  if (index > -1) {
    selected.value.splice(index, 1);
    applyFilters();
  }
};

const clearFilters = () => {
  selected.value = [];
  applyFilters();
};

const getPlatform = (platformId: string) => {
  return props.platforms.find(p => p.id === platformId);
};

const getPlatformName = (platformId: string): string => {
  return getPlatform(platformId)?.name ?? platformId;
};

const getPlatformIcon = (platformId: string): string => {
  return getPlatform(platformId)?.icon ?? 'generic';
};

const applyFilters = () => {
  const query: Record<string, string[]> = {};
  if (selected.value.length > 0) {
    query.platforms = selected.value;
  }
  router.get(games.index.url({ query }), {}, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
};

watch(() => props.selectedPlatforms, (newValue) => {
  selected.value = [...newValue];
}, { immediate: true });
</script>

<template>
  <div class="surface p-4 mb-5">
    <div class="flex flex-wrap items-end gap-3">
      <div class="flex-1 min-w-[200px]">
        <label class="block text-sm font-medium text-gray-300 mb-1.5">Platforms</label>
        <Popover class="relative">
          <PopoverButton class="w-full bg-dark-950 border border-white/10 rounded-lg px-3 py-2 text-left text-sm text-gray-300 hover:border-white/20 transition-colors flex items-center justify-between focus:outline-none focus:ring-1 focus:ring-cyan focus:border-cyan">
            <span
              v-if="selected.length === 0"
              class="text-gray-500"
            >All Platforms</span>
            <span
              v-else
              class="text-white"
            >{{ selected.length }} selected</span>
            <ChevronDownIcon class="h-4 w-4 text-white/40" />
          </PopoverButton>

          <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
          >
            <PopoverPanel class="absolute z-50 mt-2 w-full surface max-h-64 overflow-y-auto">
              <div class="p-1.5">
                <label
                  v-for="platform in platforms"
                  :key="platform.id"
                  class="flex items-center px-2.5 py-1.5 rounded-md hover:bg-white/5 cursor-pointer"
                >
                  <input
                    type="checkbox"
                    :value="platform.id"
                    :checked="selected.includes(platform.id)"
                    class="w-4 h-4 text-accent-600 bg-dark-950 border-white/20 rounded focus:ring-cyan focus:ring-1"
                    @change="togglePlatform(platform.id)"
                  >
                  <PlatformIcon
                    :icon="platform.icon"
                    size="sm"
                    class="ml-2.5 text-white/50"
                  />
                  <span class="ml-2 text-sm text-gray-300">{{ platform.name }}</span>
                </label>
              </div>
            </PopoverPanel>
          </transition>
        </Popover>
      </div>

      <button
        v-if="selected.length > 0"
        type="button"
        class="btn-ghost"
        @click="clearFilters"
      >
        Clear filters
      </button>
    </div>

    <div
      v-if="selected.length > 0"
      class="mt-3 flex flex-wrap gap-1.5"
    >
      <span
        v-for="platformId in selected"
        :key="platformId"
        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-xs font-medium bg-accent-600/15 text-accent-400 border border-accent-600/25"
      >
        <PlatformIcon
          :icon="getPlatformIcon(platformId)"
          size="xs"
        />
        {{ getPlatformName(platformId) }}
        <button
          type="button"
          class="hover:text-white transition-colors"
          :aria-label="`Remove ${getPlatformName(platformId)} filter`"
          @click="removePlatform(platformId)"
        >
          <XMarkIcon class="w-3 h-3" />
        </button>
      </span>
    </div>
  </div>
</template>
