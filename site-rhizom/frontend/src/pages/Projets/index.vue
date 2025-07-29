<template>
  <section class="h-screen w-full bg-white flex items-center">
    <!-- Carousel des projets -->
    <div class="w-full h-full overflow-hidden">
      <ProjectsCarousel :projects="projects" :slideWidth="carouselWidth" />
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ProjectsCarousel from "../../components/ProjectsCarousel.vue";
import { fetchProjects } from "../../services/api";

const projects = ref([]);
const carouselWidth = window.innerWidth / projects.value.length;

onMounted(async () => {
  projects.value = await fetchProjects();
});
</script>
