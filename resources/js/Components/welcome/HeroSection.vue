<script setup lang="ts">
import { register, login } from '@/routes';
import AppButton from '@/Components/ui/AppButton.vue';
import ShelfPreview from '@/Components/welcome/ShelfPreview.vue';
</script>

<template>
  <section class="relative flex flex-col">
    <div class="landing-intro mx-auto w-full max-w-7xl px-4 pb-6 pt-8 sm:px-6 sm:pb-8 sm:pt-10 lg:px-8 lg:pt-12">
      <p class="brand-logo select-none text-4xl sm:text-5xl lg:text-6xl">
        Sparekey
      </p>
      <h1 class="landing-line mt-4 max-w-2xl text-xl font-semibold tracking-tight text-white sm:text-2xl lg:text-[1.75rem] lg:leading-snug">
        Dump the spare keys out of Discord.
      </h1>
      <p
        class="landing-line mt-2.5 max-w-xl text-sm leading-relaxed text-white/55 sm:text-base"
        style="animation-delay: 80ms"
      >
        Private groups, claim when you want it, karma if someone always takes and never gives.
      </p>
      <div
        class="landing-line mt-6 flex flex-col items-stretch gap-2.5 sm:flex-row sm:items-center"
        style="animation-delay: 140ms"
      >
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

    <div class="landing-plane w-full">
      <ShelfPreview />
    </div>
  </section>
</template>
