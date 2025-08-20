// src/composables/useSplashState.js
import { ref } from "vue";

export function useSplashState({
  minSkipDelayMs = 5000,
  maxDurationMs = 10000,
  onTimeout,
} = {}) {
  const canSkip = ref(false);
  let tShow, tTimeout;

  function startTimers() {
    clearTimers();
    tShow = window.setTimeout(() => {
      canSkip.value = true;
    }, minSkipDelayMs);
    tTimeout = window.setTimeout(() => {
      onTimeout?.();
    }, maxDurationMs);
  }
  function clearTimers() {
    if (tShow) {
      clearTimeout(tShow);
      tShow = undefined;
    }
    if (tTimeout) {
      clearTimeout(tTimeout);
      tTimeout = undefined;
    }
  }

  return { canSkip, startTimers, clearTimers };
}
