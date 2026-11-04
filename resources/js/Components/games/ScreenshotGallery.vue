<script setup lang="ts">
import { ref, computed } from 'vue';
import ImageGalleryModal from '@/Components/shared/ImageGalleryModal.vue';
import { ScreenshotData } from '@/types/generated';

interface Props {
  screenshots: ScreenshotData[];
}

const props = defineProps<Props>();

const selectedIndex = ref(0);
const modalOpen = ref(false);

const imageUrls = computed(() =>
  props.screenshots.map(s => `https://images.igdb.com/igdb/image/upload/t_screenshot_big/${s.image_id}.jpg`),
);

const openModal = (index: number) => {
  selectedIndex.value = index;
  modalOpen.value = true;
};
</script>

<template>
  <div class="pt-2 space-y-3">
    <h2 class="text-sm font-medium uppercase tracking-wider text-white/40">
      Screenshots
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <img
        v-for="(screenshot, index) in screenshots"
        :key="screenshot.id"
        :src="imageUrls[index]"
        alt="Game Screenshot"
        class="rounded-lg border border-white/10 w-full h-auto cursor-pointer hover:border-white/25 transition-colors"
        @click="openModal(index)"
      >
    </div>

    <ImageGalleryModal
      :open="modalOpen"
      :images="imageUrls"
      :selected-index="selectedIndex"
      @close="modalOpen = false"
      @update:selected-index="selectedIndex = $event"
    />
  </div>
</template>
