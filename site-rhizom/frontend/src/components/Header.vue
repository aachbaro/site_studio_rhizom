<template>
  <header class="fixed top-0 left-0 w-full z-50 bg-transparent">
    <div class="w-full px-6 py-6 flex justify-between items-center">
      <router-link
        to="/"
        class="font-halogen text-5xl tracking-tight uppercase"
      >
        Rhizom
      </router-link>
      <nav class="hidden md:flex gap-8">
        <router-link
          v-for="link in links"
          :key="link.path"
          :to="link.path"
          class="flex items-center justify-center border border-black rounded-full px-5 h-8 text-xs lowercase leading-none hover:bg-black hover:text-white transition duration-200"
          active-class="text-black"
        >
          {{ link.name }}
        </router-link>
      </nav>
    </div>
  </header>
</template>

<script setup>
const links = [
  { name: "Projets", path: "/projets" },
  { name: "Studio", path: "/studio" },
  { name: "Contact", path: "/contact" },
];
</script>

<style scoped>
/* .nav-link {
  @apply text-base font-semibold px-4 py-2 rounded transition duration-200 hover:bg-black/80 hover:text-white;
} */
</style>
