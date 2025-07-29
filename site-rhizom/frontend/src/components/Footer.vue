<template>
  <footer class="bg-white text-black pt-12">
    <!-- Ligne du haut : INSTAGRAM E-MAIL -->
    <div class="max-w-screen-xl mx-auto px-6 md:px-12 font-halogen mb-8">
      <div class="flex flex-col items-start gap-1">
        <a
          href="https://www.instagram.com/studio.rhizom/"
          target="_blank"
          rel="noopener noreferrer"
          class="text-4xl md:text-5xl uppercase leading-none block no-underline hover:no-underline"
        >
          Instagram
        </a>
        <a
          href="mailto:contact@studiorhizom.com"
          class="text-4xl md:text-5xl uppercase leading-none block no-underline hover:no-underline"
        >
          E-mail
        </a>
      </div>
    </div>

    <!-- Grille des infos -->
    <div class="w-full border-t border-gray-200 pt-8 pb-10">
      <div class="max-w-screen-xl mx-auto px-6 md:px-12">
        <div class="flex flex-col md:flex-row md:gap-16">
          <!-- Studio -->
          <div class="mb-8 md:mb-0">
            <h3 class="text-sm uppercase mb-2">Studio</h3>
            <p class="text-xs leading-relaxed">
              10 Rue du Clos Bénard,<br />
              93300 Aubervilliers
            </p>
          </div>

          <!-- Politique -->
          <div class="mb-8 md:mb-0">
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
                class="border-b border-black focus:outline-none pb-1 placeholder-black/50"
                required
              />
              <button
                type="submit"
                class="border border-black rounded-full px-4 py-1 text-xs lowercase hover:bg-black hover:text-white transition"
              >
                s’abonner
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
