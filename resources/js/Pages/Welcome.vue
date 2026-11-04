<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import HeroSection from '@/Components/welcome/HeroSection.vue';
</script>

<template>
  <Head title="Spare keys for your friends" />

  <GuestLayout>
    <HeroSection />

    <section
      class="border-t border-white/10"
      aria-labelledby="truth-heading"
    >
      <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
        <h2
          id="truth-heading"
          class="max-w-2xl text-lg font-semibold tracking-tight text-white sm:text-xl"
        >
          Bundles leave leftovers. Friends want them. Spreadsheets suck.
        </h2>
        <p class="mt-2.5 max-w-xl text-sm leading-relaxed text-white/55 sm:text-base">
          Sparekey keeps the pool in one shelf: invite your crew, paste keys, claim without a screenshot race in chat.
        </p>
      </div>
    </section>
  </GuestLayout>
</template>
