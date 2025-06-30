<template>
  <div class="project-card">
    <img :src="project.image" :alt="project.title" />
    <h3>{{ project.title }}</h3>
    <p>{{ project.description }}</p>
  </div>
</template>

<script>
export default {
  name: 'ProjectCard',
  props: {
    project: Object
  }
}
</script>

<style scoped>
.project-card {
  border: 1px solid #ddd;
  padding: 1rem;
}
</style>
