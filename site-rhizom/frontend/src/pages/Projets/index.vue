<template>
  <section class="py-16 bg-white">
    <!-- Intro texte -->
    <div class="max-w-screen-lg mx-auto px-4 text-center md:text-left mb-12">
      <p class="text-lg leading-relaxed">
        Voici un aperçu de nos diverses compositions.<br />
        Créées pour des événements, des maisons, des moments singuliers.<br />
        Chaque projet est un dialogue entre fleurs, espace et émotion.
      </p>
    </div>

    <!-- Carousel des projets -->
    <div class="max-w-screen-lg mx-auto px-4">
      <ProjectsCarousel :projects="projects" :slideWidth="carouselWidth" />
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ProjectsCarousel from "../../components/ProjectsCarousel.vue";

const projects = ref([]);
const carouselWidth = window.innerWidth * 0.5 - 24; // 50% slide - gap

onMounted(async () => {
  // projects.json doit contenir des objets { title: string, image: "/images/projets/xxx.jpg", ... }
  const res = await fetch("/data/projects.json");
  projects.value = await res.json();
});
</script>

<style scoped>
/* pas de style additionnel pour l’instant */
</style>
