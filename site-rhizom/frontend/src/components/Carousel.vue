<template>
  <div
    class="w-screen relative left-1/2 right-1/2 -translate-x-1/2 overflow-hidden select-none"
  >
    <div class="overflow-hidden">
      <div
        class="carousel-track flex gap-6 min-w-max"
        :style="{ animationDuration: `${duration}s` }"
      >
        <template v-for="n in 2" :key="n">
          <div
            v-for="(img, i) in images"
            :key="`${n}-${img.id || i}`"
            class="flex-shrink-0 w-96 h-[28rem] relative overflow-hidden"
          >
            <img
              :src="img"
              :alt="img.title || `Slide ${i + 1}`"
              class="w-full h-full object-cover rounded"
            />
            <span class="absolute inset-0 pointer-events-none"></span>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
  images: { type: Array, required: true },
});

const duration = 60;

const track = ref(null);
let autoScrollInterval = null;

function getItemWidth() {
  // Largeur + gap, adapte si besoin
  const el = track.value?.querySelector("div");
  return el ? el.offsetWidth + 24 : 288; // 256px + 24px gap par défaut
}

onMounted(() => {
  function scrollStep() {
    if (!track.value) return;
    track.value.scrollLeft += 0.5; // vitesse douce, adapte si besoin

    // Boucle infinie si passé la moitié du scroll total
    const itemWidth = getItemWidth();
    const totalImages = props.images.length;
    const loopPoint = itemWidth * totalImages;

    if (track.value.scrollLeft >= loopPoint) {
      // On revient au début de la première copie sans flash
      track.value.scrollLeft -= loopPoint;
    }
  }

  autoScrollInterval = setInterval(scrollStep, 16); // ~60fps
});

onBeforeUnmount(() => {
  clearInterval(autoScrollInterval);
});

// Optionnel : pause si la souris passe dessus (UX +)
function pauseScroll() {
  clearInterval(autoScrollInterval);
}
</script>

<style scoped>
.carousel-track {
  animation: scroll-x linear infinite;
}
@keyframes scroll-x {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}
</style>
