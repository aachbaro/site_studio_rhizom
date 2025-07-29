<template>
  <header
    class="static md:fixed md:top-0 md:left-0 md:w-full z-50 bg-transparent"
  >
    <div class="w-full px-6 py-4 flex justify-between items-center">
      <router-link
        to="/"
        class="font-halogen text-3xl uppercase tracking-tight"
      >
        Rhizom
      </router-link>

      <!-- Desktop Navigation (inchangée) -->
      <nav class="hidden md:flex gap-6">
        <router-link
          v-for="link in links"
          :key="link.path"
          :to="link.path"
          class="flex items-center justify-center border border-black rounded-full px-4 py-2 text-sm lowercase hover:bg-black hover:text-white transition"
          active-class="text-black"
        >
          {{ link.name }}
        </router-link>
      </nav>

      <!-- Mobile Hamburger Icon -->
      <button @click="toggleMenu" class="md:hidden focus:outline-none">
        <span class="sr-only">Ouvrir le menu</span>
        <svg
          v-if="!isOpen"
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"
          />
        </svg>
        <svg
          v-else
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    </div>

    <!-- Mobile Navigation Drawer -->
    <transition name="slide-down">
      <nav v-if="isOpen" class="md:hidden bg-white border-t border-gray-200">
        <ul class="flex flex-col px-6 py-4 space-y-4">
          <li v-for="link in links" :key="link.path">
            <router-link
              :to="link.path"
              class="block text-base font-medium lowercase"
              @click="closeMenu"
            >
              {{ link.name }}
            </router-link>
          </li>
        </ul>
      </nav>
    </transition>
  </header>
</template>

<script setup>
import { ref } from "vue";

const links = [
  { name: "Projets", path: "/projets" },
  { name: "Studio", path: "/studio" },
  { name: "Contact", path: "/contact" },
];

const isOpen = ref(false);
const toggleMenu = () => {
  isOpen.value = !isOpen.value;
};
const closeMenu = () => {
  isOpen.value = false;
};
</script>

<style scoped>
/* Transition for mobile menu */
.slide-down-enter-active {
  transition: max-height 0.3s ease-out;
}
.slide-down-leave-active {
  transition: max-height 0.2s ease-in;
}
.slide-down-enter-from,
.slide-down-leave-to {
  max-height: 0;
  overflow: hidden;
}
.slide-down-enter-to,
.slide-down-leave-from {
  max-height: 300px;
}
</style>
