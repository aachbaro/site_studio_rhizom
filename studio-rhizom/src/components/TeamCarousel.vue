<template>
  <div class="relative w-full">
    <!-- piste scrollable -->
    <div ref="track" class="overflow-hidden">
      <div class="flex gap-6 snap-x snap-mandatory overflow-x-auto scroll-smooth">
        <div
          v-for="(member, i) in members"
          :key="i"
          class="flex-shrink-0 w-64 snap-start"
        >
          <img
            :src="member.image"
            :alt="member.name"
            class="w-full h-80 object-cover rounded-md"
          />
          <h4 class="mt-3 font-halogen text-lg">{{ member.name }}</h4>
          <p class="text-sm leading-snug">{{ member.description }}</p>
        </div>
      </div>
    </div>

    <!-- flèches -->
    <button
      @click="scroll(-1)"
      class="absolute left-2 top-1/2 transform -translate-y-1/2 text-2xl opacity-60 hover:opacity-100 transition"
      aria-label="Précédent"
    >←</button>
    <button
      @click="scroll(1)"
      class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xl opacity-60 hover:opacity-100 transition"
      aria-label="Suivant"
    >→</button>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  members: {
    type: Array,
    required: true
  },
  slideWidth: {
    type: Number,
    default: 256 + 24  // équivaut à w-64 (256px) + gap-6 (24px)
  }
})

const track = ref(null)

function scroll(dir) {
  track.value.scrollBy({ left: dir * props.slideWidth, behavior: 'smooth' })
}
</script>