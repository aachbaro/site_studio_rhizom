<template>
  <div class="min-h-screen bg-white text-black font-sans">
    <NavBar />
    <main class="max-w-screen-lg mx-auto px-4 py-8">
      <slot />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import NavBar from '../components/NavBar.vue'
import Footer from '../components/Footer.vue'
</script>
