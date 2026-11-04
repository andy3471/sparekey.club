<script setup lang="ts">
import AppCombobox from '@/Components/ui/AppCombobox.vue';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import type { ComboboxItem } from '@/types/ui';
import { autocomplete } from '@/routes';

interface Props {
  id: string;
  inputClass?: string;
  ariaLabel?: string;
}

withDefaults(defineProps<Props>(), {
  inputClass: 'w-full pl-9 pr-3 py-1.5 bg-dark-950 border border-white/10 text-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan focus:border-cyan transition-colors placeholder-gray-500',
  ariaLabel: 'Search games',
});

const emit = defineEmits<{
  select: [item: ComboboxItem];
  search: [query: string];
}>();

const handleSelect = (item: ComboboxItem | null) => {
  if (item) emit('select', item);
};
</script>

<template>
  <div class="relative">
    <AppCombobox
      :id="id"
      name="search"
      placeholder="Search games..."
      :url="autocomplete.url()"
      :input-class="inputClass"
      :aria-label="ariaLabel"
      @update:model-value="handleSelect"
      @search="emit('search', $event)"
    />
    <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
  </div>
</template>
