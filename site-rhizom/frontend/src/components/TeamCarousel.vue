<template>
  <div class="relative w-full">
    <div ref="track" class="overflow-x-auto md:overflow-x-hidden">
      <div
        class="flex gap-6 md:gap-0 snap-x snap-mandatory scroll-smooth track w-fit md:w-full"
      >
        <div
          v-for="(member, i) in members"
          :key="i"
          class="flex-shrink-0 snap-start w-[90vw] md:w-1/4 px-1 flex flex-col items-start"
        >
          <img
            :src="member.image"
            :alt="member.name"
            class="w-full object-contain"
          />
          <p class="mt-8 text-xl leading-snug">
            {{ member.description }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  members: {
    type: Array,
    required: true,
  },
  slideWidth: {
    type: Number,
    default: 256 + 24, // équivaut à w-64 (256px) + gap-6 (24px)
  },
});

const track = ref(null);
</script>

<style scoped>
.track::-webkit-scrollbar {
  display: none;
}
.track {
  scrollbar-width: none !important;
  -ms-overflow-style: none;
}
</style>
