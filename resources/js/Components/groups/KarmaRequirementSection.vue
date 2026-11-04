<script setup lang="ts">
import FormInput from '@/Components/ui/FormInput.vue';

interface Props {
  modelValue: number | string | null;
  error?: string;
}

defineProps<Props>();

const emit = defineEmits<{
  'update:modelValue': [value: string | number | null];
}>();
</script>

<template>
  <div class="border-t border-white/10 pt-5">
    <h3 class="text-xs font-medium uppercase tracking-wider text-white/40 mb-3">
      Karma Requirement
    </h3>
    <FormInput
      id="min_karma"
      :model-value="modelValue"
      label="Minimum karma to claim keys"
      type="number"
      placeholder="No minimum"
      :error="error"
      hint="Members with karma below this threshold won't be able to claim keys in this group. Leave empty for no restriction."
      @update:model-value="emit('update:modelValue', $event)"
    >
      <template #label-suffix>
        <span class="text-gray-500"> (optional)</span>
      </template>
    </FormInput>
  </div>
</template>
