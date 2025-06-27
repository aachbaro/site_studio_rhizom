<template>
  <header class="w-full bg-white text-black border-b border-black/10">
    <div class="max-w-screen-lg mx-auto px-6 py-4 flex justify-between items-center">
      <router-link to="/" class="text-lg font-medium tracking-tight hover:opacity-80 transition">
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
    </div>
  </header>
</template>

<script setup>
import { onMounted } from 'vue'

const links = [
  { name: 'Collectif', path: '/collectif' },
  { name: 'Projets',   path: '/projets'   },
  { name: 'Catalogue', path: '/catalogue' },
  { name: 'Contact',   path: '/contact'   }
]

onMounted(() => {
  console.log('🧭 NavBar monté, links =', links)
})
</script>