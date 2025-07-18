<template>
  <footer class="bg-white text-black pt-12">
    <!-- Ligne du haut : INSTAGRAM E-MAIL -->
    <div
      class="w-full font-halogen px-6 md:px-12 flex flex-col md:flex-row md:items-baseline gap-0 mb-5"
    >
      <div class="flex flex-col md:flex-row md:gap-12">
        <span class="text-4xl md:text-5xl uppercase leading-none block"
          >Instagram</span
        >
        <span class="text-4xl md:text-5xl uppercase leading-none block md:ml-12"
          >E-mail</span
        >
      </div>
    </div>

    <!-- Grille des infos -->
    <div class="w-full border-t border-gray-200 pt-8 pb-10">
      <div
        class="px-6 md:px-12 grid grid-cols-1 md:grid-cols-3 gap-12"
        style="max-width: 90rem; margin-left: 0"
      >
        <!-- Studio -->
        <div>
          <h3 class="text-sm uppercase mb-2">Studio</h3>
          <p class="text-xs leading-relaxed">
            10 Rue du Clos Bénard,<br />
            93300 Aubervilliers
          </p>
        </div>
        <!-- Politique -->
        <div>
          <h3 class="text-sm uppercase mb-2">Politique</h3>
          <ul class="text-xs space-y-1">
            <li>
              <router-link to="/cookies" class="hover:underline">
                Politique de cookies
              </router-link>
            </li>
            <li>
              <router-link to="/mentions-legales" class="hover:underline">
                Mentions légales
              </router-link>
            </li>
          </ul>
        </div>
        <!-- S’abonner -->
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
    </div>
  </footer>
</template>
