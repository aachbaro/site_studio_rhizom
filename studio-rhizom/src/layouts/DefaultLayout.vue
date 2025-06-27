<script setup>
import NavBar from '../components/NavBar.vue'
import Footer from '../components/Footer.vue'
</script>

<template>
  <div class="min-h-screen bg-white text-black font-sans">
    <NavBar />
    <main class="px-4 py-8 max-w-screen-lg mx-auto">
      <slot />
    </main>
    <Footer />
  </div>
</template>