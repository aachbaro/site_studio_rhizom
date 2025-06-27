<template>
  <div class="min-h-screen font-sans bg-neutral-950 text-white flex flex-col">
    <NavBar />
    <main class="flex-1">
      <slot />
    </main>
    <Footer />
  </div>
</template>

<script>
import NavBar from '../components/NavBar.vue'
import Footer from '../components/Footer.vue'

export default {
  name: 'DefaultLayout',
  components: { NavBar, Footer }
}
</script>

<style scoped>
</style>
