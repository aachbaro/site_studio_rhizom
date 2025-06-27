<template>
  <nav class="p-4 bg-neutral-900">
    <ul class="flex space-x-4">
      <li><RouterLink to="/">Accueil</RouterLink></li>
      <li><RouterLink to="/collectif">Collectif</RouterLink></li>
      <li><RouterLink to="/projets">Projets</RouterLink></li>
      <li><RouterLink to="/catalogue">Catalogue</RouterLink></li>
      <li><RouterLink to="/contact">Contact</RouterLink></li>
    </ul>
  </nav>
</template>

<script>
import { RouterLink } from 'vue-router'

export default {
  name: 'NavBar',
  components: { RouterLink }
}
</script>

<style scoped>
</style>
