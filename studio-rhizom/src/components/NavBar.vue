<template>
  <header class="bg-white text-black border-b border-gray-200 font-sans">
    <div class="max-w-screen-lg mx-auto w-full px-6 py-4 flex justify-between items-center">
      <router-link to="/" class="text-lg font-semibold tracking-wide hover:text-primary transition">
        Rhizom
      </router-link>

      <nav class="hidden md:flex gap-6 text-sm">
        <router-link
          v-for="link in links"
          :key="link.path"
          :to="link.path"
          class="hover:text-primary transition"
          active-class="text-primary"
        >
          {{ link.name }}
        </router-link>
      </nav>

      <!-- Mobile menu (à venir si besoin) -->
    </div>
  </header>
</template>

<script setup>
const links = [
  { name: 'Collectif', path: '/collectif' },
  { name: 'Projets', path: '/projets' },
  { name: 'Catalogue', path: '/catalogue' },
  { name: 'Contact', path: '/contact' }
]
</script>
