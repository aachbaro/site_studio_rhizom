<template>
  <footer class="bg-white text-black font-halogen">
    <!-- Zone supérieure : titre + flèches -->
    <div
      class="max-w-screen-lg mx-auto px-6 py-8 flex justify-between items-center"
    >
      <h2 class="text-2xl uppercase">Instagram</h2>
    </div>

    <!-- Zone inférieure : 3 colonnes / stack mobile -->
    <div
      class="max-w-screen-lg mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-gray-200"
    >
      <!-- STUDIO -->
      <div>
        <h3 class="text-sm uppercase mb-2">Studio</h3>
        <p class="text-xs leading-relaxed">
          10 Rue du Clos Bénard,<br />
          93300 Aubervilliers
        </p>
      </div>

      <!-- POLITIQUE -->
      <div>
        <h3 class="text-sm uppercase mb-2">Politique</h3>
        <ul class="text-xs space-y-1">
          <li><a href="#" class="hover:underline">Politique de cookies</a></li>
          <li><a href="#" class="hover:underline">Mentions légales</a></li>
        </ul>
      </div>

      <!-- S’ABONNER -->
      <div>
        <h3 class="text-sm uppercase mb-2">S’abonner</h3>
        <form class="flex flex-col sm:flex-row sm:items-center gap-4">
          <label for="email" class="sr-only">E-mail</label>
          <input
            id="email"
            type="email"
            placeholder="E-mail *"
            class="border-b border-black focus:outline-none flex-1 pb-1 placeholder-black/50"
            required
          />
          <button
            type="submit"
            class="border border-black rounded-full px-4 py-1 text-xs uppercase hover:bg-black hover:text-white transition"
          >
            s’abonner
          </button>
        </form>
      </div>
    </div>

    <!-- Bas de footer -->
    <div class="bg-gray-900 text-gray-400 text-xs text-center py-4">Rhizom</div>
  </footer>
</template>

<script setup>
// Pas de logique spécifique pour l'instant
</script>

<style scoped>
/* Si besoin d’ajustements spécifiques, tu peux les ajouter ici */
</style>
