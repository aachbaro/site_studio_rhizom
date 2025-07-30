<template>
  <section
    class="w-full bg-white md:h-screen h-auto md:flex md:items-center items-start"
  >
    <div class="w-full overflow-hidden md:pb-0 pb-12">
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
