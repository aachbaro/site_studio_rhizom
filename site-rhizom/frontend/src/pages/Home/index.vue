<template>
  <section class="min-h-screen flex-col bg-white flex items-center">
    <div class="w-full px-4 md:px-8">
      <div
        class="grid grid-cols-1 md:grid-cols-12 items-center md:gap-10 relative isolate"
      >
        <!-- Colonne texte -->
        <div
          class="order-2 md:order-2 md:col-span-5 relative z-20 flex flex-col justify-center pt-6"
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
        <div
          class="order-1 md:order-1 md:col-span-7 relative z-10 flex justify-center md:justify-end"
        >
          <!-- Conteneur fixe -->
          <!-- Conteneur fixe -->
          <div
            class="relative w-full md:w-auto h-[65vh] md:h-[100vh] rounded shadow-lg overflow-hidden"
          >
            <!-- Skeleton discret PENDANT l’essai -->
            <Transition name="fade">
              <div
                v-if="!heroAttempted && !heroReady"
                class="absolute inset-0 bg-neutral-200 motion-safe:animate-pulse"
              >
                <div
                  class="absolute inset-0 bg-gradient-to-b from-black/5 to-transparent"
                ></div>
              </div>
            </Transition>

            <!-- Image si et seulement si elle a été chargée -->
            <Transition name="fade">
              <SmartImage
                v-if="heroReady"
                :src="heroSrc"
                :alt="heroAlt"
                eager
                :intrinsic="{ width: 1440, height: 2160 }"
                sizes="(min-width:1024px) 50vw, 90vw"
                wrapperClass="absolute inset-0 w-full h-full"
                imgClass="block w-full h-full object-cover"
              />
            </Transition>
          </div>
        </div>
      </div>

      <!-- Carousel : monté seulement quand prêt (indépendant) -->
      <div class="w-full overflow-hidden px-4 mt-16 select-none mx-auto">
        <Carousel v-if="carouselReady" :images="images" />
        <div v-else class="h-[40vh] md:h-[50vh] bg-neutral-100 rounded"></div>
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
const carouselReady = ref(false);

const heroSrc = ref("");
const heroAlt = ref("Image d'accueil");
const heroReady = ref(false);

// lance les 2 chargements en parallèle
onMounted(() => {
  loadHero();
  loadCarousel();
});

async function loadHero() {
  try {
    const random = await getRandomHeroImage(); // { url, alt } | null

    // Pas d'URL => on arrête, pas d'image
    if (!random?.url) {
      heroAttempted.value = true;
      return;
    }

    const url = random.url;
    const alt = random.alt || "";

    // Précharge puis affiche si OK
    const pre = new Image();
    pre.onload = () => {
      heroSrc.value = url;
      heroAlt.value = alt;
      heroReady.value = true;
      heroAttempted.value = true;
    };
    pre.onerror = () => {
      // Échec : pas d'image, pas de skeleton permanent
      heroAttempted.value = true;
    };
    pre.src = url;
  } catch {
    // Erreur API : pas d'image
    heroAttempted.value = true;
  }
}

async function loadCarousel() {
  try {
    const data = await fetchCarousel();
    images.value = (data || []).map((img) => img.url);
  } finally {
    carouselReady.value = true; // monte le carrousel dès que la liste est là
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.35s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
