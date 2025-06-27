<template>
  <header class="w-full px-6 py-4 flex justify-between items-center border-b border-white/10">
    <router-link to="/" class="text-lg font-semibold tracking-wide hover:opacity-80 transition">
      Rhizom
    </router-link>

    <nav class="hidden md:flex gap-6 text-sm">
      <router-link
        v-for="link in links"
        :key="link.path"
        :to="link.path"
        class="hover:opacity-80 transition"
        active-class="text-primary"
      >
        {{ link.name }}
      </router-link>
    </nav>

    <!-- Mobile menu (à venir si besoin) -->
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
