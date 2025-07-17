<template>
  <div class="relative w-full">
    <!-- Conteneur scrollable -->
    <div ref="track" class="overflow-hidden">
      <div
        class="flex gap-6 snap-x snap-mandatory overflow-x-auto scroll-smooth"
      >
        <div
          v-for="(img, i) in images"
          :key="img.id || i"
          class="flex-shrink-0 w-64 h-80 snap-start"
        >
          <img
            :src="img"
            :alt="img.title || `Slide ${i + 1}`"
            class="w-full h-full object-cover rounded"
          />
        </div>
      </div>
    </div>

    <!-- Flèches -->
    <button
      @click="scroll(-1)"
      class="absolute left-2 top-1/2 transform -translate-y-1/2 text-2xl opacity-60 hover:opacity-100 transition"
      aria-label="Précédent"
    >
      ←
    </button>
    <button
      @click="scroll(1)"
      class="absolute right-2 top-1/2 transform -translate-y-1/2 text-2xl opacity-60 hover:opacity-100 transition"
      aria-label="Suivant"
    >
      →
    </button>
  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  images: {
    type: Array,
    required: true,
  },
  /** largeur d'un slide (en px) + gap pour le scroll */
  slideWidth: {
    type: Number,
    default: 256 + 24, // correspond à w-64 (256px) + gap-6 (24px)
  },
});

const track = ref(null);

/** scroll directions: -1 = gauche, +1 = droite */
function scroll(direction) {
  if (!track.value) return;
  track.value.scrollBy({
    left: direction * props.slideWidth,
    behavior: "smooth",
  });
}
</script>

<style scoped>
/* masquage de la scrollbar si tu veux */
.track::-webkit-scrollbar {
  display: none;
}
</style>
