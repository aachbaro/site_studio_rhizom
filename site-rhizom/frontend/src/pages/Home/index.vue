<template>
  <section class="min-h-screen flex-col bg-white flex items-center">
    <div class="w-full px-4 md:px-8">
      <div class="grid grid-cols-1 md:grid-cols-12 items-center md:gap-10">
        <!-- Colonne texte (à droite sur desktop) -->
        <div
          class="order-2 md:order-2 md:col-span-5 flex flex-col justify-center pt-6"
        >
          <h1
            class="font-halogen font-bold uppercase leading-[0.9] tracking-tight text-[13vw] md:text-[7.5vw] xl:text-[6rem] mb-4 md:mb-6 text-center md:text-left"
          >
            <span class="block">Studio</span>
            <span class="block md:relative md:left-[-10rem]">Rhizom</span>
          </h1>

          <p
            class="text-[clamp(0.95rem,1.2vw,1.125rem)] leading-relaxed md:leading-7 max-w-[42ch] md:max-w-[47ch] mx-auto md:mx-0 mb-6 md:mb-8 text-center md:text-left"
          >
            Rhizom est un studio de design floral constitué de plusieurs
            sensibilités artistiques. Notre approche nous situe à la frontière
            entre la botanique, l’artisanat et l’art. Nous travaillons la fleur
            et le végétal à la manière d’une oeuvre plastique. La matière
            devient sujet et se transforme sous le geste. Elle prend pied sur un
            kenzan, se plonge dans un vase ou se déploie dans l’espace comme une
            scénographie.
          </p>

          <router-link to="/projets" class="btn-thin self-center md:self-start">
            Découvrez notre travail.
          </router-link>
        </div>

        <!-- Colonne image (à gauche sur desktop) -->
        <div
          class="order-1 md:order-1 md:col-span-7 flex justify-center md:justify-end"
        >
          <img
            :src="heroSrc"
            alt="Grande composition florale"
            class="h-[65vh] md:h-[100vh] w-auto object-cover shadow-lg"
          />
        </div>
      </div>

      <!-- Carousel -->
      <div class="w-full overflow-hidden px-4 mt-16 select-none mx-auto">
        <Carousel :images="images" />
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import Carousel from "../../components/Carousel.vue";
import { fetchCarousel } from "../../services/api";

const images = ref([]);
const heroIndex = ref(0);
const heroSrc = computed(() => "/static/home/ImageHome/Grandeimage.png");

onMounted(async () => {
  const data = await fetchCarousel();
  images.value = (data || []).map((img) => img.url);
});
</script>
