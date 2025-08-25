<template>
  <section class="min-h-screen flex-col bg-white flex items-center">
    <div class="w-full px-4 md:px-8">
      <div class="grid grid-cols-1 md:grid-cols-12 items-center md:gap-10">
        <!-- Colonne texte -->
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

        <!-- Colonne image -->
        <!-- Template (remplace juste la partie image) -->
        <div
          class="order-1 md:order-1 md:col-span-7 flex justify-center md:justify-end"
        >
          <!-- Conteneur skeleton pour éviter le saut de mise en page -->
          <div
            class="h-[65vh] md:h-[100vh] w-full md:w-auto bg-gray-100 rounded shadow-lg flex items-center justify-center"
            v-if="!heroReady"
          >
            <span class="text-sm text-gray-400">Chargement…</span>
          </div>

          <SmartImage
            v-else
            :src="heroSrc"
            :alt="heroAlt"
            eager
            :intrinsic="{ width: 1440, height: 2160 }"
            sizes="(min-width:1024px) 50vw, 90vw"
            imgClass="h-[65vh] md:h-[100vh] w-auto object-cover shadow-lg"
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
import { ref, onMounted } from "vue";
import { fetchCarousel, getRandomHeroImage } from "../../services/api";
import Carousel from "../../components/Carousel.vue";
import SmartImage from "../../components/SmartImage.vue";

const images = ref([]);

// état pour le hero
const heroSrc = ref("");
const heroAlt = ref("Image d'accueil");
const heroReady = ref(false); // on n’affiche l’img qu’une fois prête

onMounted(async () => {
  // carrousel (inchangé)
  const data = await fetchCarousel();
  images.value = (data || []).map((img) => img.url);

  // précharger l’image aléatoire avant de l’afficher
  try {
    const random = await getRandomHeroImage(); // {url, alt} | null
    if (random?.url) {
      const pre = new Image();
      pre.onload = () => {
        heroSrc.value = random.url;
        heroAlt.value = random.alt || "Image d'accueil";
        heroReady.value = true;
      };
      pre.onerror = () => {
        // fallback silencieux si erreur réseau
        heroSrc.value = "/static/home/ImageHome/Grandeimage.png";
        heroAlt.value = "Image d'accueil";
        heroReady.value = true;
      };
      pre.src = random.url;
    } else {
      heroSrc.value = "/static/home/ImageHome/Grandeimage.png";
      heroAlt.value = "Image d'accueil";
      heroReady.value = true;
    }
  } catch {
    heroSrc.value = "/static/home/ImageHome/Grandeimage.png";
    heroAlt.value = "Image d'accueil";
    heroReady.value = true;
  }
});
</script>
