<template>
  <div>
    <h1>Nos Projets</h1>
    <ProjectCard v-for="p in projects" :key="p.title" :project="p" />
  </div>
</template>

<script>
import ProjectCard from '../components/ProjectCard.vue'
import projects from '../../public/data/projects.json'

export default {
  name: 'ProjectsPage',
  components: { ProjectCard },
  data() {
    return { projects }
  }
}
</script>

<style scoped>
</style>
