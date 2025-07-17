<template>
  <div
    v-if="visible"
    :style="{
      left: x + 'px',
      top: y + 'px',
      width: cursorSize + 'px',
      height: cursorSize + 'px',
      transform: 'translate(-50%, -50%) scale(' + scale + ')',
      transition: 'transform 0.13s cubic-bezier(.25,1,.5,1)',
    }"
    class="fixed pointer-events-none z-[10000] rounded-full cursor-blend"
  ></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
const x = ref(window.innerWidth / 2);
const y = ref(window.innerHeight / 2);
const cursorSize = ref(48);
const scale = ref(1.0);
const isHovering = ref(false);
const visible = ref(true);

function move(e) {
  x.value = e.clientX;
  y.value = e.clientY;
}
function hoverEnter() {
  scale.value = 1.6;
  isHovering.value = true;
}
function hoverLeave() {
  scale.value = 1.0;
  isHovering.value = false;
}

function onMouseLeave() {
  visible.value = false;
}
function onMouseEnter() {
  visible.value = true;
}

onMounted(() => {
  document.body.style.cursor = "none";
  window.addEventListener("mousemove", move);

  // Sélecteurs des éléments interactifs
  const interactifs = document.querySelectorAll(
    'button, a, [role="button"], input[type="button"], input[type="submit"]'
  );
  interactifs.forEach((el) => {
    el.addEventListener("mouseenter", hoverEnter);
    el.addEventListener("mouseleave", hoverLeave);
  });
  window.addEventListener("mouseout", onMouseLeave); // <--- corrige ici
  window.addEventListener("mouseover", onMouseEnter); // <--- et ici
});

onUnmounted(() => {
  document.body.style.cursor = "";
  window.removeEventListener("mousemove", move);
  const interactifs = document.querySelectorAll(
    'button, a, [role="button"], input[type="button"], input[type="submit"]'
  );
  interactifs.forEach((el) => {
    el.removeEventListener("mouseenter", hoverEnter);
    el.removeEventListener("mouseleave", hoverLeave);
  });
  window.removeEventListener("mouseout", onMouseLeave); // cleanup ici
  window.removeEventListener("mouseover", onMouseEnter); // et ici
});
</script>

<style scoped>
.cursor-blend {
  background: white;
  mix-blend-mode: difference;
  opacity: 1;
  pointer-events: none;
}
</style>
