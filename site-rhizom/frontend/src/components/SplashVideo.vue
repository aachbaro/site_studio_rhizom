<template>
  <div
    class="fixed inset-0 z-[100] bg-black transition-opacity duration-5000"
    :class="
      exiting
        ? 'opacity-0 pointer-events-none' /* CHANGÉ: click-through pendant le fade */
        : 'opacity-100 pointer-events-auto' /* CHANGÉ: interactions actives quand playing */
    "
  >
    <video
      ref="vid"
      class="absolute inset-0 w-full h-full object-cover"
      :src="src"
      preload="metadata"
      autoplay
      muted
      playsinline
      webkit-playsinline
      @ended="exit"
      @error="onError"
    />

    <!-- Logo centré, rendu par slot -->
    <div
      class="absolute inset-0 flex items-center justify-center pointer-events-none"
    >
      <slot name="logo" />
    </div>

    <!-- Bouton Passer : bas-droite + fondu -->
    <button
      rounded-full
      px-4
      py-1
      text-sm
      class="absolute bottom-6 right-6 rounded-full border transition-opacity duration-500 border-white/70 text-white/90 px-7 py-1"
      :class="canSkip ? 'opacity-100' : 'opacity-0 pointer-events-none'"
      @click="exit"
      aria-label="Passer la vidéo d’introduction"
    >
      passer
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useSplashState } from "../composables/useSplashState";
import { usePreloader } from "../composables/usePreloader";
import { assetsToPreload } from "../services/assetsToPreload";

const props = defineProps({
  src: { type: String, required: true },
  minSkipDelayMs: { type: Number, default: 3000 },
  maxDurationMs: { type: Number, default: 10000 },
});
const emit = defineEmits(["exit", "error"]);

const vid = ref(null);
const exiting = ref(false);

const { canSkip, startTimers, clearTimers } = useSplashState({
  minSkipDelayMs: props.minSkipDelayMs,
  maxDurationMs: props.maxDurationMs,
  onTimeout: () => exit(),
});
const { start: startPreload, cancel: cancelPreload } = usePreloader();

onMounted(async () => {
  startTimers();
  // Préchargement en parallèle (tu peux enrichir la liste dans assetsToPreload)
  startPreload(assetsToPreload());
  try {
    await vid.value?.play();
  } catch {
    // autoplay bloqué : on laisse juste apparaître le bouton à 5s
  }
});
onBeforeUnmount(() => {
  clearTimers();
  cancelPreload();
});

function onError(e) {
  emit("error", e);
}
function exit() {
  clearTimers();
  exiting.value = true; // → lance le fondu (500ms)
  setTimeout(() => emit("exit"), 5000); // → on sort après le fondu
}
</script>
