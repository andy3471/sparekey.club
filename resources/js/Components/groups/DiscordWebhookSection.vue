<script setup lang="ts">
import FormInput from '@/Components/ui/FormInput.vue';
import { CheckIcon } from '@heroicons/vue/24/outline';

interface Props {
  modelValue: string;
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
      Discord Integration
    </h3>
    <FormInput
      id="discord_webhook_url"
      :model-value="modelValue"
      label="Webhook URL"
      type="url"
      placeholder="https://discord.com/api/webhooks/..."
      :error="error"
      hint="Get notified in Discord when keys are shared or claimed. Create a webhook in your Discord channel's settings under Integrations → Webhooks."
      @update:model-value="emit('update:modelValue', $event)"
    >
      <template #label-suffix>
        <span class="text-gray-500"> (optional)</span>
      </template>
    </FormInput>
    <div
      v-if="modelValue"
      class="mt-2 flex items-center gap-2 text-xs"
    >
      <span class="inline-flex items-center gap-1 text-success">
        <CheckIcon class="w-3.5 h-3.5" />
        Discord notifications enabled
      </span>
    </div>
  </div>
</template>
