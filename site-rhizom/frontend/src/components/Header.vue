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

      <!-- Desktop -->
      <nav class="hidden md:flex gap-6">
        <router-link
          v-for="link in links"
          :key="link.path"
          :to="link.path"
          :aria-current="isActivePath(link.path) ? 'page' : undefined"
          :class="[
            'btn-nav border-black lowercase', // base
            isActivePath(link.path)
              ? 'btn-nav--active' // actif = inversé
              : 'hover:bg-black hover:text-white', // au survol sinon
          ]"
        >
          {{ link.name }}
        </router-link>
      </nav>

      <!-- Mobile Hamburger -->
      <button
        @click="toggleMenu"
        class="md:hidden focus:outline-none"
        aria-label="Ouvrir le menu"
      >
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

    <!-- Mobile Drawer -->
    <transition name="slide-down">
      <nav v-if="isOpen" class="md:hidden bg-white border-t border-gray-200">
        <ul class="flex flex-col px-6 py-4 space-y-4">
          <li v-for="link in links" :key="link.path">
            <router-link
              :to="link.path"
              @click="closeMenu"
              :aria-current="isActivePath(link.path) ? 'page' : undefined"
              :class="[
                'btn-nav w-full justify-center', // base mobile
                isActivePath(link.path)
                  ? 'btn-nav--active'
                  : 'hover:bg-black hover:text-white',
              ]"
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
import { useRoute } from "vue-router";

const links = [
  { name: "Projets", path: "/projets" },
  { name: "le studio", path: "/studio" },
  { name: "Contact", path: "/contact" },
];

const route = useRoute();
const isOpen = ref(false);
const toggleMenu = () => (isOpen.value = !isOpen.value);
const closeMenu = () => (isOpen.value = false);

// actif si route exacte OU sous-chemin (/projets/xxx)
const isActivePath = (p) => route.path === p || route.path.startsWith(p + "/");
</script>
