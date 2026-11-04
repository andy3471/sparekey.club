<script setup lang="ts">
import { ref } from 'vue';
import ConfirmModal from '@/Components/ui/ConfirmModal.vue';

const emit = defineEmits<{
  delete: [];
}>();

const showDeleteModal = ref(false);

const confirmDelete = () => {
  showDeleteModal.value = false;
  emit('delete');
};
</script>

<template>
  <div class="surface p-5 sm:p-6 border-danger-600/30">
    <h2 class="text-sm font-medium text-danger-400 mb-1.5">
      Danger Zone
    </h2>
    <p class="text-white/45 text-sm mb-4">
      Deleting the group will permanently remove all data associated with it. Keys shared within this group will become ungrouped.
    </p>
    <button
      type="button"
      class="btn-danger"
      @click="showDeleteModal = true"
    >
      Delete Group
    </button>

    <ConfirmModal
      :open="showDeleteModal"
      title="Delete group"
      message="Members will be removed and keys shared in this group will become ungrouped. This cannot be undone."
      confirm-label="Delete group"
      variant="danger"
      @confirm="confirmDelete"
      @cancel="showDeleteModal = false"
    />
  </div>
</template>
