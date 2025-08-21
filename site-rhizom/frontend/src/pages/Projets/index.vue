<!-- Home section -->
<template>
  <section
    class="w-full bg-white md:min-h-[100svh] min-h-[100svh] md:flex md:items-center"
  >
    <div class="w-full overflow-hidden md:pb-0 pb-12">
      <ProjectsCarousel :projects="projects" />
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ProjectsCarousel from "../../components/ProjectsCarousel.vue";
import { fetchProjects } from "../../services/api";

const projects = ref([]);

onMounted(async () => {
  projects.value = await fetchProjects();
});
</script>
