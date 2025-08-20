// src/composables/usePrefersReducedMotion.js
import { ref, onMounted, onBeforeUnmount } from "vue";
export function usePrefersReducedMotion() {
  const reduced = ref(false);
  let mql;
  const onChange = (e) => {
    reduced.value = e.matches;
  };
  onMounted(() => {
    mql = window.matchMedia("(prefers-reduced-motion: reduce)");
    reduced.value = mql.matches;
    mql.addEventListener?.("change", onChange);
  });
  onBeforeUnmount(() => mql?.removeEventListener?.("change", onChange));
  return reduced;
}
