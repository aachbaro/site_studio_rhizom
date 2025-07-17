<template>
  <header class="fixed top-0 left-0 w-full z-50 bg-transparent">
    <div
      class="max-w-screen-lg mx-auto px-6 py-4 flex justify-between items-center"
    >
      <router-link
        to="/"
        class="text-3xl font-extrabold tracking-tight uppercase"
        style="letter-spacing: 0.02em; font-family: 'Inter', sans-serif"
      >
        Rhizom
      </router-link>
      <nav class="hidden md:flex gap-8">
        <router-link
          v-for="link in links"
          :key="link.path"
          :to="link.path"
          class="inline-block border border-black rounded-full px-5 py-1 text-[10px] uppercase hover:bg-black hover:text-white transition duration-200"
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
