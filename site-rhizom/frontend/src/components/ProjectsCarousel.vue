<template>
  <div class="relative w-full md:h-screen">
    <!-- Piste scrollable -->
    <div
      ref="track"
      class="overflow-x-auto scroll-smooth snap-x snap-mandatory flex hide-scrollbar"
    >
      <div
        v-for="(p, i) in projects"
        :key="i"
        class="md:h-full md:w-auto object-contain flex-shrink-0 snap-start flex flex-col items-center justify-start relative overflow-hidden"
        :style="{ width: slideW + 'px' }"
      >
        <img
          :src="p.url"
          :alt="p.title"
          class="w-full h-auto object-cover md:h-screen h-auto"
        />
        <!-- Titre mobile sous l'image -->
        <div class="md:hidden w-full text-center mt-2">
          <h3 class="text-2xl uppercase mt-5">{{ p.title }}</h3>
        </div>
        <!-- Overlay titre (desktop) -->
        <div
          class="hidden md:flex absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center"
        >
          <span class="text-white text-3xl text-center uppercase">
            {{ p.title }}
          </span>
        </div>
      </div>
    </div>

    <!-- Flèches desktop uniquement -->
    <button
      @click="scroll(-1)"
      class="hidden md:block absolute left-2 top-1/2 transform -translate-y-1/2 text-2xl opacity-60 hover:opacity-100 p-2 z-10"
      aria-label="Précédent"
    >
      ←
    </button>
    <button
      @click="scroll(1)"
      class="hidden md:block absolute right-2 top-1/2 transform -translate-y-1/2 text-2xl opacity-60 hover:opacity-100 p-2 z-10"
      aria-label="Suivant"
    >
      →
    </button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";

const props = defineProps({
  projects: {
    type: Array,
    required: true,
  },
  // largeur de chaque slide en desktop (calculée en parent)
  slideWidth: {
    type: Number,
    required: true,
  },
});

// Réactivité sur la largeur d'écran
const width = ref(window.innerWidth);
const updateWidth = () => {
  width.value = window.innerWidth;
};
onMounted(() => window.addEventListener("resize", updateWidth));
onUnmounted(() => window.removeEventListener("resize", updateWidth));

// largeur utilisée pour chaque slide: plein écran sur mobile, valeur parent en desktop
const slideW = computed(() =>
  width.value < 768 ? width.value : props.slideWidth
);

const track = ref(null);
const scroll = (dir) => {
  if (!track.value) return;
  const container = track.value;
  const slideEl = container.querySelector("div.snap-start");
  if (!slideEl) return;
  const step = slideEl.clientWidth;
  container.scrollBy({ left: dir * step, behavior: "smooth" });
};
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none; /* IE/Edge */
  scrollbar-width: none; /* Firefox */
}
</style>
