<script setup lang="ts">
const year = new Date().getFullYear();
</script>

<template>
  <footer class="relative z-10 mt-auto border-t border-white/10 bg-dark-950/80">
    <div class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
      <p class="text-sm text-white/55">
        Built by
        <span class="brand-logo marker-wiggle ml-1 inline-block text-sm leading-none">
          Andrew Hargrave
        </span>
      </p>
      <div class="flex items-center gap-4 text-sm">
        <a
          href="https://andyh.app"
          target="_blank"
          rel="noopener noreferrer"
          class="text-white/55 transition-colors hover:text-white"
        >Portfolio</a>
        <a
          href="https://github.com/andy3471/sparekey.club"
          target="_blank"
          rel="noopener noreferrer"
          class="text-white/55 transition-colors hover:text-white"
        >GitHub</a>
        <a
          href="https://ko-fi.com/andy3471#payment-widget"
          target="_blank"
          rel="noopener noreferrer"
          class="text-white/55 transition-colors hover:text-white"
        >Ko-fi</a>
        <span class="text-xs text-white/40">© {{ year }}</span>
      </div>
    </div>
  </footer>
</template>
