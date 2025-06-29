<template>
  <div class="px-4 py-8 max-w-screen-lg mx-auto">
    <PageTitle>Nos Projets</PageTitle>
    <div class="grid md:grid-cols-2 gap-8">
      <ProjectCard v-for="p in projects" :key="p.title" :project="p" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ProjectCard from '../../components/ProjectCard.vue'
import PageTitle from '../../components/PageTitle.vue'

const projects = ref([])

onMounted(async () => {
  const res = await fetch('/data/projects.json')
  projects.value = await res.json()
})
</script>

<style scoped>
/* tu peux ajouter des styles custom ici */
</style>