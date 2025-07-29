<template>
  <section class="min-h-screen flex-col bg-white flex items-center">
    <div
      class="w-full flex flex-col-reverse md:flex-row items-center justify-center"
    >
      <!-- Image -->
      <div class="w-full md:w-[55%] flex justify-center md:justify-end">
        <img
          src="/static/home/ImageHome/Grandeimage.png"
          alt="Grande composition florale"
          class="w-auto h-[70vh] md:h-[100vh] object-cover shadow-lg"
        />
      </div>
      <!-- Texte -->
      <div
        class="w-full md:w-[45%] flex flex-col justify-center md:justify-center md:pl-4 px-4 gap-10"
      >
        <h1
          class="text-6xl md:text-8xl uppercase font-halogen font-bold mb-6 leading-tight text-center md:text-left"
        >
          Studio<br />
          <span class="block md:relative md:left-[-10rem]">Rhizom</span>
        </h1>
        <p
          class="text-base md:text-xl leading-relaxed mb-8 text-center md:text-left"
        >
          Chez Rhizom, chaque création est une composition vivante. <br />
          Nous travaillons la fleur comme une matière brute, sensible, à la
          frontière de l’art. Pas de recette. Pas de standard. Seulement des
          formes, des textures.<br />
          Découvrez ici quelques fragments de nos projets.
        </p>
        <router-link
          to="/projets"
          class="w-fit mb-8 self-center md:self-start inline-block border border-black rounded-full px-6 py-2 text-base hover:bg-black hover:text-white transition md:relative"
        >
          tous les projets
        </router-link>
      </div>
    </div>
    <!-- Carousel (inchangé) -->
    <div class="w-full overflow-hidden px-4 mt-16 select-none mx-auto">
      <Carousel :images="images" />
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Carousel from "../../components/Carousel.vue";
import { fetchCarousel } from "../../services/api";

const images = ref([]);

// On récupère les images du backend via le service API
onMounted(async () => {
  const data = await fetchCarousel();
  // On mappe pour ne garder que les chemins d’image (champ url)
  images.value = (data || []).map((img) => img.url);
});
</script>
