<template>
  <component :is="wrapperTag" class="relative" :class="wrapperClass">
    <picture v-if="sources && sources.length">
      <source
        v-for="(s, i) in sources"
        :key="i"
        :type="s.type"
        :srcset="s.srcset"
        :sizes="s.sizes || sizes || undefined"
      />
      <img
        ref="imgEl"
        :src="resolvedSrc"
        :srcset="srcset || undefined"
        :sizes="sizes || undefined"
        :alt="alt || ''"
        :width="intrinsic?.width || undefined"
        :height="intrinsic?.height || undefined"
        :loading="eager ? 'eager' : 'lazy'"
        decoding="async"
        :fetchpriority="eager ? 'high' : fetchPriority"
        :class="[
          imgClass,
          fade ? 'motion-safe:transition-opacity' : '',
          'block',
        ]"
        :style="imgStyle"
        @load="onLoad"
      />
    </picture>

    <img
      v-else
      ref="imgEl"
      :src="resolvedSrc"
      :srcset="srcset || undefined"
      :sizes="sizes || undefined"
      :alt="alt || ''"
      :width="intrinsic?.width || undefined"
      :height="intrinsic?.height || undefined"
      :loading="eager ? 'eager' : 'lazy'"
      decoding="async"
      :fetchpriority="eager ? 'high' : fetchPriority"
      :class="[imgClass, fade ? 'motion-safe:transition-opacity' : '', 'block']"
      :style="imgStyle"
      @load="onLoad"
    />

    <!-- Blur-up placeholder (cross-fade) -->
    <div
      v-if="placeholder && (!loaded || forceKeepPlaceholder)"
      class="absolute inset-0 pointer-events-none"
      :style="placeholderStyle"
    />
  </component>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue";

/**
 * Props
 */
const props = defineProps({
  src: { type: String, required: true },
  alt: { type: String, default: "" },
  eager: { type: Boolean, default: false },
  fetchPriority: { type: String, default: "auto" },
  sizes: { type: String, default: "" },
  srcset: { type: String, default: "" },
  sources: { type: Array, default: () => [] },
  intrinsic: { type: Object, default: null },
  placeholder: { type: String, default: "" },
  wrapperTag: { type: String, default: "div" },
  wrapperClass: { type: String, default: "" },
  imgClass: { type: String, default: "w-full h-auto object-cover" },

  /** Nouveau : fade-in configurable */
  fade: { type: Boolean, default: true },
  fadeDuration: { type: Number, default: 450 }, // ms
  /** Laisse le placeholder si tu veux (debug / comparaisons) */
  forceKeepPlaceholder: { type: Boolean, default: false },
});

const loaded = ref(false);
const imgEl = ref(null);

const resolvedSrc = computed(() => props.src);

/* Styles dynamiques */
const imgStyle = computed(() => {
  if (!props.fade) return null;
  return {
    opacity: loaded.value ? 1 : 0,
    transition: `opacity ${props.fadeDuration}ms ease-out`,
    willChange: "opacity",
  };
});

const placeholderStyle = computed(() => ({
  backgroundImage: `url(${props.placeholder})`,
  backgroundSize: "cover",
  backgroundPosition: "center",
  filter: "blur(12px)",
  transform: "scale(1.05)",
  opacity: loaded.value ? 0 : 1,
  transition: `opacity ${props.fadeDuration}ms ease-out`,
}));

function onLoad() {
  // même si l'image est déjà en cache, on force une frame pour déclencher le fondu
  requestAnimationFrame(() => {
    loaded.value = true;
  });
}

onMounted(async () => {
  await nextTick();
  if (imgEl.value && imgEl.value.complete) {
    // image récupérée du cache → déclenche quand même le fade
    requestAnimationFrame(() => {
      loaded.value = true;
    });
  }
});
</script>
