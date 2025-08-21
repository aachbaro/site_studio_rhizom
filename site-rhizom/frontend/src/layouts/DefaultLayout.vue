<script setup>
import Header from "../components/Header.vue";
import Footer from "../components/Footer.vue";
</script>

<template>
  <div class="flex flex-col min-h-screen w-full bg-white text-black">
    <!-- <Header /> -->
    <!-- Contenu principal : prend tout l'espace restant -->
    <main class="flex-grow w-full px-0 py-0">
      <slot />
    </main>

    <!-- Footer : toujours en bas -->
    <Footer />
  </div>
</template>
