<!-- src/components/SplashVideo.vue -->
<template>
  <div class="fixed inset-0 z-[9999] bg-black">
    <video
      ref="vid"
      class="absolute inset-0 w-full h-full object-cover"
      :src="src"
      :poster="poster"
      autoplay
      muted
      playsinline
      webkit-playsinline
      @ended="exit"
      @error="onError"
    />
    <div
      class="absolute inset-0 flex items-center justify-center pointer-events-none"
    >
      <slot name="logo" />
    </div>
    <button
      v-if="canSkip"
      class="absolute bottom-6 left-1/2 -translate-x-1/2 rounded-full px-4 py-2 border border-white/70 text-white/90 backdrop-blur-sm"
      @click="exit"
      aria-label="Passer la vidéo d’introduction"
    >
      Passer
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { useSplashState } from "../composables/useSplashState";
import { usePreloader } from "../composables/usePreloader";
import { assetsToPreload } from "../services/assetsToPreload";

const props = defineProps({
  src: String,
  poster: String,
  minSkipDelayMs: { type: Number, default: 5000 },
  maxDurationMs: { type: Number, default: 10000 },
});
const emit = defineEmits(["exit", "error"]);

const vid = ref(null);
const { canSkip, startTimers, clearTimers } = useSplashState({
  minSkipDelayMs: props.minSkipDelayMs,
  maxDurationMs: props.maxDurationMs,
  onTimeout: () => exit(),
});
const { start: startPreload, cancel: cancelPreload } = usePreloader();

onMounted(async () => {
  startTimers();
  startPreload(assetsToPreload());
  try {
    await vid.value?.play();
  } catch {
    /* autoplay bloqué → on laisse le bouton apparaître à 5s */
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
  emit("exit");
}
</script>
