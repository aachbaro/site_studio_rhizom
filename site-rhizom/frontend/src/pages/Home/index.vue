<template>
  <section class="py-16 bg-white">
    <!-- 1. Flex container héro -->
    <div
      class="max-w-screen-lg mx-auto flex flex-col-reverse md:flex-row items-center gap-12"
    >
      <!-- Texte -->
      <div class="flex-1 text-center md:text-left">
        <h1 class="font-halogen text-7xl font-black">STUDIO<br />RHIZOM</h1>
        <p class="text-lg md:text-xl leading-snug">
          De l’art. Des fleurs.<br />
          Des poèmes qu’on ne récite pas.
        </p>
      </div>

      <!-- Image -->
      <div class="flex-1">
        <img
          src="/static/home/ImageHome/Grandeimage.png"
          alt="Grande composition florale"
          class="w-full h-auto object-cover rounded-md shadow-lg"
        />
      </div>
    </div>
    <!-- ← on ferme bien ici le flex container héro -->

    <!-- 2. Paragraphe descriptif + bouton (en dehors du flex) -->
    <div class="max-w-screen-lg mx-auto text-center mt-12 px-4">
      <p class="text-base md:text-lg leading-relaxed mb-6">
        Chez Rhizom, chaque création est une composition vivante.<br />
        Nous travaillons la fleur comme une matière brute, sensible, à la
        frontière de l’art.<br />
        Pas de recette. Pas de standard. Seulement des formes, des textures.<br />
        Découvrez ici quelques fragments de nos projets.
      </p>
      <router-link
        to="/projets"
        class="inline-block border border-black rounded-full px-6 py-2 text-sm uppercase hover:bg-black hover:text-white transition"
      >
        Tous les projets
      </router-link>
    </div>
    <div class="max-w-screen-lg mx-auto mt-16 px-4">
      <Carousel :images="images" />
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Carousel from "../../components/Carousel.vue";

const images = ref([]);

// On récupère depuis le backend
onMounted(async () => {
  const res = await fetch("/api/carousel");
  const data = await res.json();
  // On mappe pour ne garder que les chemins d’image (champ url)
  images.value = data.map((img) => img.url);
});
</script>

<style scoped>
/* aucun style additionnel */
</style>
