<template>
  <div class="min-h-screen bg-background text-foreground font-sans">
    <NavBar />
    <main class="px-4 py-8">
      <slot />
    </main>
    <Footer />
  </div>
</template>

<script setup>
import NavBar from '../components/NavBar.vue'
import Footer from '../components/Footer.vue'
</script>
