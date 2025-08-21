<template>
  <section class="bg-white">
    <div
      class="max-w-screen-xl mx-auto px-6 md:px-12 py-16 flex flex-col items-start"
    >
      <h1 class="text-3xl font-bold mb-8">Politique de cookies</h1>

      <!-- Intro -->
      <p class="mb-6">
        Lors de votre navigation sur le site de Studio Rhizom, des cookies
        peuvent être déposés sur votre terminal (ordinateur, tablette, mobile).
      </p>

      <!-- Définition -->
      <h2 class="text-xl font-semibold mt-8 mb-2">
        Qu'est-ce qu'un cookie&nbsp;?
      </h2>
      <p class="mb-6">
        Un cookie est un petit fichier texte enregistré sur votre appareil lors
        de la consultation d'un site internet. Il permet de conserver des
        données de navigation pour faciliter l'expérience utilisateur ou mesurer
        l'audience du site.
      </p>

      <!-- Types utilisés -->
      <h2 class="text-xl font-semibold mt-8 mb-2">
        Quels cookies utilisons-nous&nbsp;?
      </h2>
      <p class="mb-4">
        Le site de Studio Rhizom utilise des cookies strictement nécessaires à
        son bon fonctionnement, ainsi que des cookies de mesure d'audience de
        type Google Analytics.
      </p>
      <ul class="mb-6 list-disc pl-5 space-y-1">
        <li>
          <span class="font-semibold">Cookies fonctionnels&nbsp;:</span>
          nécessaires à la navigation et à certaines fonctionnalités du site.
        </li>
        <li>
          <span class="font-semibold">Cookies de mesure d'audience&nbsp;:</span>
          nous permettent de connaître la fréquentation et les performances du
          site.
        </li>
      </ul>

      <!-- Consentement -->
      <h2 class="text-xl font-semibold mt-8 mb-2">Consentement</h2>
      <p class="mb-6">
        Lors de votre première visite, un bandeau vous informe de l'utilisation
        de cookies. Vous pouvez accepter ou refuser leur dépôt, à l'exception
        des cookies techniques indispensables au bon fonctionnement du site.
      </p>

      <!-- Gestion -->
      <h2 class="text-xl font-semibold mt-8 mb-2">Gestion des cookies</h2>
      <p class="mb-4">
        Vous pouvez à tout moment modifier vos préférences en matière de cookies
        en accédant au module de gestion des cookies.
      </p>

      <!-- Bouton pour ouvrir le module -->
      <button
        type="button"
        @click="openCookieSettings"
        class="mb-6 border border-black rounded-full px-5 py-2 text-sm lowercase hover:bg-black hover:text-white transition"
        aria-label="Ouvrir le module de gestion des cookies"
      >
        gérer mes cookies
      </button>

      <p class="mb-6">
        Vous pouvez également configurer votre navigateur pour bloquer ou
        supprimer les cookies.
      </p>

      <!-- En savoir plus -->
      <h2 class="text-xl font-semibold mt-8 mb-2">En savoir plus</h2>
      <p>
        Pour plus d'informations sur les cookies et vos droits, vous pouvez
        consulter le site de la CNIL&nbsp;:
        <a
          href="https://www.cnil.fr"
          target="_blank"
          rel="noopener"
          class="underline"
        >
          www.cnil.fr
        </a>
      </p>
    </div>
  </section>
</template>

<script setup>
/**
 * Essaie d’ouvrir le module de gestion des cookies si un CMP est présent.
 * Compatibilité : OneTrust, CookieYes, Axeptio, Didomi (les plus courants).
 * Fallback : déclenche un événement que vous pouvez capter ailleurs dans l’app.
 */
function openCookieSettings() {
  // OneTrust
  if (
    window.OneTrust &&
    typeof window.OneTrust.ToggleInfoDisplay === "function"
  ) {
    window.OneTrust.ToggleInfoDisplay();
    return;
  }
  // CookieYes
  if (
    window.cookieyes &&
    typeof window.cookieyes.resetSettings === "function"
  ) {
    window.cookieyes.resetSettings();
    return;
  }
  // Axeptio
  if (window._axcb) {
    window._axcb.push(function (sdk) {
      sdk.showConsentModal();
    });
    return;
  }
  // Didomi
  if (window.Didomi && typeof window.Didomi.openPreferences === "function") {
    window.Didomi.openPreferences();
    return;
  }

  // Fallback : event custom (à écouter dans votre layout)
  window.dispatchEvent(new CustomEvent("open-cookie-preferences"));

  // Option de secours : ancre
  const el = document.getElementById("cookie-preferences");
  if (el && typeof el.scrollIntoView === "function") {
    el.scrollIntoView({ behavior: "smooth", block: "start" });
  }
}
</script>

<style scoped>
a {
  color: black;
}
</style>
