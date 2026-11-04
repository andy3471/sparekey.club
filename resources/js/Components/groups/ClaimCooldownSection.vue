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
      Claim Cooldown
    </h3>
    <FormInput
      id="claim_cooldown_minutes"
      :model-value="modelValue"
      label="Cooldown between claims (minutes)"
      type="number"
      placeholder="No cooldown"
      :error="error"
      hint="After claiming a key, members must wait this many minutes before claiming another in this group. Leave empty for no restriction."
      @update:model-value="emit('update:modelValue', $event)"
    >
      <template #label-suffix>
        <span class="text-gray-500"> (optional)</span>
      </template>
    </FormInput>
  </div>
</template>
