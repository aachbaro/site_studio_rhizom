<template>
  <div class="relative w-full">
    <!-- Piste scrollable -->
    <div ref="track" class="overflow-hidden">
      <div
        class="flex gap-6 snap-x snap-mandatory overflow-x-auto scroll-smooth"
      >
        <div
          v-for="(p, i) in projects"
          :key="i"
          class="flex-shrink-0 w-[calc(50%-1.5rem)] snap-start relative"
        >
          <img
            :src="p.url"
            :alt="p.title"
            class="w-full h-auto object-cover rounded-md"
          />
          <!-- Overlay titre au survol -->
          <div
            class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center rounded-md"
          >
            <span class="text-white font-halogen text-3xl">{{ p.title }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Flèches -->
    <button
      @click="scroll(-1)"
      class="absolute left-2 top-1/2 transform -translate-y-1/2 text-2xl opacity-60 hover:opacity-100 p-2"
      aria-label="Précédent"
    >
      ←
    </button>
    <button
      @click="scroll(1)"
      class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xl opacity-60 hover:opacity-100 p-2"
      aria-label="Suivant"
    >
      →
    </button>
  </div>
</template>

<script setup>
import { ref } from "vue";
const props = defineProps({
  projects: {
    type: Array,
    required: true,
  },
  /** largeur de chaque slide (50% de la zone - gap) */
  slideWidth: {
    type: Number,
    default: window.innerWidth * 0.5 - 24, // ajustable au besoin
  },
});

const track = ref(null);
function scroll(dir) {
  if (!track.value) return;
  track.value.scrollBy({
    left: dir * props.slideWidth,
    behavior: "smooth",
  });
}
</script>

<style scoped>
/* tu peux masquer la scrollbar si tu veux */
.track::-webkit-scrollbar {
  display: none;
}
</style>
