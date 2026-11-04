<script setup lang="ts">
import { register } from '@/routes';
import AppButton from '@/Components/ui/AppButton.vue';
</script>

<template>
  <section class="border-t border-white/10 py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-lg mx-auto text-center">
        <h2 class="text-xl sm:text-2xl font-semibold text-white tracking-tight mb-3">
          Put the keys somewhere useful
        </h2>
        <p class="text-white/55 text-sm sm:text-base leading-relaxed mb-7">
          Free to use. Make a group when you’re ready.
        </p>
        <AppButton :href="register.url()">
          Create an account
        </AppButton>
      </div>
    </div>
  </section>
</template>
