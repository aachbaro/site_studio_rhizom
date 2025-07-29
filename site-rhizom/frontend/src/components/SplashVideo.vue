<template>
  <div
    v-if="visible"
    class="fixed inset-0 bg-black flex items-center justify-center z-50"
  >
    <video
      ref="video"
      :src="src"
      autoplay
      muted
      playsinline
      @ended="onEnded"
      class="max-w-full max-h-full"
    ></video>
    <div
      v-if="fading"
      class="absolute inset-0 bg-black transition-opacity duration-700 opacity-100"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
  src: { type: String, required: true },
});
const emit = defineEmits(["ended"]);

const visible = ref(true);
const fading = ref(false);

function onEnded() {
  fading.value = true;
  setTimeout(() => {
    visible.value = false;
    localStorage.setItem("hasSeenSplash", "true");
    document.body.classList.add("splash-ended");
    emit("ended");
  }, 700); // durée du fade-out
}

onMounted(() => {
  if (localStorage.getItem("hasSeenSplash") === "true") {
    visible.value = false;
    document.body.classList.add("splash-ended");
  }
});
</script>

<style scoped>
/* Le <div> de fond passe de opacity: 0 à 100 via la classe fade */
.transition-opacity {
  transition-property: opacity;
}
</style>
