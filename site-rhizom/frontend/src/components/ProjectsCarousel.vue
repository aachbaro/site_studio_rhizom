<template>
  <div class="relative w-full md:pt-[vh] md:h-[100svh] h-auto">
    <!-- Piste scrollable -->
    <div
      ref="track"
      class="overflow-x-auto scroll-smooth snap-x snap-mandatory flex hide-scrollbar snap-track"
    >
      <!-- SLIDE : items-stretch + hauteur -->
      <div
        v-for="(p, i) in projects"
        :key="i"
        class="flex-none snap-start relative overflow-hidden flex flex-col items-stretch"
        :style="{ width: slideW + 'px' }"
      >
        <!-- SmartImage : wrapper et image qui remplissent -->
        <SmartImage
          :src="p.url"
          :alt="p.title"
          :placeholder="p.blur || ''"
          :intrinsic="p.intrinsic || null"
          :srcset="p.srcset || ''"
          :sizes="p.sizes || carouselSizes"
          wrapperClass="w-full h-auto md:h-[100svh]"
          imgClass="block w-full h-full object-cover"
        />

        <!-- Titre mobile (ok, espace vertical assumé) -->
        <div
          class="font-urw font-black text-[28px] leading-[1.05] tracking-tight uppercase md:hidden w-full text-center mt-2"
        >
          <h3 class="text-2xl uppercase mt-5">{{ p.title }}</h3>
        </div>

        <!-- Overlay desktop -->
        <div
          class="hidden md:flex absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-300 items-center justify-center"
        >
          <span
            class="text-white text-[1.200rem] leading-tight text-center uppercase font-guide"
          >
            {{ p.title }}
          </span>
        </div>
      </div>
    </div>

    <!-- Flèche gauche -->
    <button
      @click="scroll(-1)"
      class="hidden md:flex items-center justify-center absolute left-6 top-1/2 -translate-y-1/2 h-20 text-white"
      aria-label="Précédent"
    >
      <svg
        class="w-12 h-12"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <path d="M22 12 H3" />
        <path d="M10 5 L3 12 L10 19" />
      </svg>
    </button>

    <!-- Flèche droite -->
    <button
      @click="scroll(1)"
      class="hidden md:flex items-center justify-center absolute right-6 top-1/2 -translate-y-1/2 h-20 text-white"
      aria-label="Suivant"
    >
      <svg
        class="w-12 h-12 rotate-180"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <path d="M22 12 H3" />
        <path d="M10 5 L3 12 L10 19" />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import SmartImage from "./SmartImage.vue";

const props = defineProps({
  projects: { type: Array, required: true },
});

const carouselSizes = "(min-width:1280px) 33vw, (min-width:768px) 50vw, 90vw";

const width = ref(window.innerWidth);
const updateWidth = () => (width.value = window.innerWidth);
onMounted(() => window.addEventListener("resize", updateWidth));
onUnmounted(() => window.removeEventListener("resize", updateWidth));

// Largeur d'un slide :
// - mobile: 100% viewport (1 colonne)
// - desktop: 1/3 viewport (3 colonnes visibles)
const slideW = computed(() =>
  width.value < 768 ? width.value : Math.floor(width.value / 3)
);

const track = ref(null);
const scroll = (dir) => {
  const container = track.value;
  if (!container) return;
  const step = slideW.value;
  container.scrollBy({ left: dir * step, behavior: "smooth" });
};
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
/* Safari inertie + snap fiable */
.snap-track {
  -webkit-overflow-scrolling: touch;
  scroll-snap-type: x mandatory;
}
</style>
