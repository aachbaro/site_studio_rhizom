<template>
  <!-- Root overlay -->
  <div
    v-if="visible"
    class="fixed inset-0 z-50 bg-black flex items-center justify-center"
    :aria-hidden="true"
  >
    <!-- Video full-bleed -->
    <video
      ref="videoEl"
      :src="src"
      :poster="poster || undefined"
      autoplay
      muted
      playsinline
      preload="auto"
      @ended="handleEnded"
      @error="handleError"
      class="absolute inset-0 w-full h-full object-cover"
    ></video>

    <!-- Optional overlay slot (ex: logo) -->
    <div class="relative z-10">
      <slot />
    </div>

    <!-- Fade to black on end -->
    <div
      v-if="fading"
      class="absolute inset-0 bg-black transition-opacity duration-700 opacity-100"
    ></div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, computed } from "vue";

/**
 * Props
 * - replayTtlMinutes: si déjà vu, on re‑montre la vidéo seulement si la dernière vue > TTL
 * - poster: image fallback affichée avant que la vidéo ne démarre (et pour iOS)
 */
const props = defineProps<{
  src: string;
  poster?: string | null;
  replayTtlMinutes?: number; // ex: 60 pour 1h
  storageKey?: string; // override du nom de clé LS si besoin
}>();

const emit = defineEmits<{
  (e: "ended"): void;
  (e: "error", err: unknown): void;
  (e: "shown"): void;
}>();

/* ------------------------------
   Local Storage utils (TTL)
--------------------------------*/
const KEY = computed(() => props.storageKey ?? "splash_seen_at");
const now = () => Date.now();

function hasToShow(): boolean {
  const ttlMs = (props.replayTtlMinutes ?? 0) * 60_000;
  if (ttlMs <= 0) return true; // toujours montrer si TTL non défini
  const raw = localStorage.getItem(KEY.value);
  if (!raw) return true;
  const last = Number(raw);
  if (!Number.isFinite(last)) return true;
  return now() - last > ttlMs;
}

function markSeen() {
  localStorage.setItem(KEY.value, String(now()));
}

/* ------------------------------
   State
--------------------------------*/
const visible = ref(false);
const fading = ref(false);
const videoEl = ref<HTMLVideoElement | null>(null);

function lockScroll() {
  document.documentElement.classList.add("has-splash");
  document.body.classList.add("has-splash");
}
function unlockScroll() {
  document.documentElement.classList.remove("has-splash");
  document.body.classList.remove("has-splash");
}

function start() {
  visible.value = true;
  lockScroll();
  emit("shown");
  // Respect "prefers-reduced-motion": si true → on coupe direct (accessibilité)
  if (window.matchMedia?.("(prefers-reduced-motion: reduce)")?.matches) {
    // on simule un end immédiat
    handleEnded();
  } else {
    // auto‑play “best effort”: Safari iOS peut retarder malgré muted
    queueMicrotask(() => {
      videoEl.value?.play().catch(() => {
        // si ça échoue, on laisse l’utilisateur voir le poster; on ne bloque pas
      });
    });
  }
}

function stopWithFade() {
  fading.value = true;
  window.setTimeout(() => {
    visible.value = false;
    fading.value = false;
    unlockScroll();
  }, 700); // doit matcher duration-700
}

/* ------------------------------
   Handlers
--------------------------------*/
function handleEnded() {
  markSeen();
  stopWithFade();
  emit("ended");
}

function handleError(e: unknown) {
  // En cas de problème vidéo: on ne bloque pas l’app → on ferme
  markSeen();
  stopWithFade();
  emit("error", e);
}

onMounted(() => {
  if (hasToShow()) {
    start();
  } else {
    // déjà vu dans le TTL → rien à afficher
    visible.value = false;
  }
});

onBeforeUnmount(() => {
  unlockScroll();
});
</script>

<style scoped>
/* Pas de styles lourds ici; tout passe par Tailwind.
   On ne met que les classes globales de verrouillage scroll,
   mais en "scoped" ça ne s'appliquera pas au body/html.
*/
</style>
