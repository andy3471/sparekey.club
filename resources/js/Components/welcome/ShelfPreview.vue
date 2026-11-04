<script setup lang="ts">
const covers = [
  { title: 'Bundle leftover', platform: 'Steam', keys: 3, tone: 'from-zinc-700 to-zinc-900' },
  { title: 'Humble dupe', platform: 'Steam', keys: 1, tone: 'from-stone-700 to-stone-900' },
  { title: 'Epic freebie', platform: 'Epic', keys: 2, tone: 'from-neutral-700 to-neutral-950' },
  { title: 'Fanatical spare', platform: 'GOG', keys: 1, tone: 'from-zinc-600 to-zinc-900' },
  { title: 'Prime claim', platform: 'Steam', keys: 4, tone: 'from-stone-600 to-stone-950' },
  { title: 'Giveaway win', platform: 'Xbox', keys: 1, tone: 'from-neutral-600 to-neutral-900' },
  { title: 'Twitch drop', platform: 'Steam', keys: 2, tone: 'from-zinc-700 to-black' },
  { title: 'Friend pass', platform: 'PSN', keys: 1, tone: 'from-stone-700 to-black' },
];
</script>

<template>
  <div
    class="shelf-plane border-t border-white/10 bg-dark-950"
    aria-hidden="true"
  >
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-7 lg:px-8">
      <div class="mb-3.5 flex items-baseline justify-between gap-3">
        <p class="text-[11px] font-medium uppercase tracking-[0.14em] text-white/35">
          Your group shelf
        </p>
        <p class="text-[11px] text-white/30">
          Keys stay hidden until claimed
        </p>
      </div>

      <div class="grid grid-cols-4 gap-2 sm:grid-cols-6 md:grid-cols-8 md:gap-2.5">
        <div
          v-for="(cover, index) in covers"
          :key="cover.title"
          class="shelf-cover group relative aspect-[3/4] overflow-hidden border border-white/10 bg-gradient-to-b"
          :class="cover.tone"
          :style="{ animationDelay: `${120 + index * 45}ms` }"
        >
          <div class="absolute inset-x-0 top-0 flex items-center justify-between px-1.5 py-1">
            <span class="rounded-sm bg-black/55 px-1 py-0.5 text-[9px] font-medium text-white/70">
              {{ cover.platform }}
            </span>
            <span class="rounded-sm bg-accent-600 px-1 py-0.5 text-[9px] font-semibold text-white">
              {{ cover.keys }}
            </span>
          </div>
          <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 via-black/55 to-transparent px-1.5 pb-2 pt-8">
            <p class="text-[10px] font-medium leading-tight text-white/85 sm:text-[11px]">
              {{ cover.title }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
