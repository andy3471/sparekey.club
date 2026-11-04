<script setup lang="ts">
import { register, login } from '@/routes';
import AppButton from '@/Components/ui/AppButton.vue';
</script>

<template>
  <section class="relative min-h-[70vh] sm:min-h-[78vh] flex items-center overflow-hidden">
    <div
      class="pointer-events-none absolute inset-y-0 right-0 w-1/2 max-w-xl opacity-40 hidden lg:block"
      aria-hidden="true"
    >
      <div class="absolute inset-0 bg-gradient-to-l from-accent-600/15 via-transparent to-transparent" />
      <div class="absolute right-12 top-1/4 bottom-1/4 w-px bg-white/10" />
      <div class="absolute right-24 top-1/3 bottom-1/3 w-px bg-white/5" />
    </div>

    <div class="relative max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-20 pb-16 sm:pt-28 sm:pb-24">
      <div class="max-w-2xl">
        <p class="brand-logo text-4xl sm:text-5xl lg:text-6xl mb-6">
          Sparekey
        </p>
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-white tracking-tight text-balance mb-4">
          Extra game keys, shared with your friends.
        </h1>
        <p class="text-sm sm:text-base text-white/60 max-w-lg leading-relaxed mb-8">
          Bundle leftovers and gift keys live in one place. Groups handle invites, claims, and who shared what, without a spreadsheet in the Discord chat.
        </p>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
          <AppButton :href="register.url()">
            Create an account
          </AppButton>
          <AppButton
            :href="login.url()"
            variant="secondary"
          >
            Sign in
          </AppButton>
        </div>
      </div>
    </div>
  </section>
</template>
