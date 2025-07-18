<script setup>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
</script>

<template>
  <div class="flex flex-col min-h-screen bg-white text-black">
    <Header />
    <!-- Contenu principal : prend tout l'espace restant -->
    <main class="flex-grow px-4 py-8 max-w-screen-lg mx-auto">
      <slot />
    </main>

    <!-- Footer : toujours en bas -->
    <Footer />
  </div>
</template>
