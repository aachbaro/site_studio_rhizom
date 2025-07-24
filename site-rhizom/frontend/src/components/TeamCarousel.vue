<template>
  <div class="relative w-full">
    <!-- piste scrollable -->
    <div ref="track" class="overflow-hidden">
      <div
        class="flex gap-12 snap-x snap-mandatory overflow-x-auto scroll-smooth track"
      >
        <div
          v-for="(member, i) in members"
          :key="i"
          class="flex-shrink-0 max-w-[90vw] snap-start"
        >
          <img
            :src="member.image"
            :alt="member.name"
            class="w-full h-[48rem] max-h-[90vh] object-cover"
          />
          <!-- <h4 class="font-halogen text-3xl">{{ member.name }}</h4> -->
          <p class="mt-8 text-xl leading-snug">{{ member.description }}</p>
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
