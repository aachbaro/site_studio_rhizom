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
        :decoding="'async'"
        :fetchpriority="eager ? 'high' : fetchPriority"
        :class="imgClass"
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
      :decoding="'async'"
      :fetchpriority="eager ? 'high' : fetchPriority"
      :class="imgClass"
      @load="onLoad"
    />

    <!-- Blur-up placeholder -->
    <div
      v-if="placeholder && !loaded"
      class="absolute inset-0 pointer-events-none transition-opacity duration-500"
      :class="loaded ? 'opacity-0' : 'opacity-100'"
      :style="{
        backgroundImage: `url(${placeholder})`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        filter: 'blur(12px)',
        transform: 'scale(1.05)'
      }"
    />
  </component>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

/**
 * Props:
 * - src: string (obligatoire)
 * - alt: string
 * - eager: bool (si true => eager + fetchpriority=high)
 * - fetchPriority: 'auto'|'low' (par défaut 'auto' quand pas eager)
 * - sizes, srcset: strings prêts (optionnels)
 * - sources: [{ type:'image/avif'|'image/webp'|..., srcset:string, sizes?:string }]
 * - intrinsic: { width:number, height:number } pour éviter le CLS (optionnel)
 * - placeholder: dataURL/base64 très petite (optionnel)
 * - wrapperTag: 'div'|'figure'..., wrapperClass, imgClass: classes Tailwind
 */
const props = defineProps({
  src: { type: String, required: true },
  alt: { type: String, default: '' },
  eager: { type: Boolean, default: false },
  fetchPriority: { type: String, default: 'auto' },
  sizes: { type: String, default: '' },
  srcset: { type: String, default: '' },
  sources: { type: Array, default: () => [] },
  intrinsic: { type: Object, default: null },
  placeholder: { type: String, default: '' },
  wrapperTag: { type: String, default: 'div' },
  wrapperClass: { type: String, default: '' },
  imgClass: { type: String, default: 'w-full h-auto object-cover' }
});

const loaded = ref(false);
const imgEl = ref(null);

// Si besoin, on pourrait différer réellement l'attribution du src (IO) — ici on garde simple
const resolvedSrc = computed(() => props.src);

function onLoad() {
  loaded.value = true;
}

onMounted(() => {
  // si image déjà en cache → onLoad ne déclenche pas toujours
  if (imgEl.value && imgEl.value.complete) {
    loaded.value = true;
  }
});
</script>

