<template>
  <div
    class="snap-y snap-mandatory h-screen overflow-y-scroll scroll-smooth hide-scrollbar"
  >
    <!-- Intro : plein écran + visuel panoramique -->
    <section id="team" class="snap-start min-h-screen bg-white">
      <div class="mx-auto max-w-[1600px] px-6 lg:px-12 py-12 md:py-16 flex">
        <div class="w-full grid grid-cols-1 content-center gap-10">
          <div class="mx-auto max-w-3xl">
            <p
              class="text-[clamp(1rem,1.2vw,1.125rem)] md:text-[clamp(1.125rem,1.3vw,1.25rem)] leading-relaxed"
            >
              Nous sommes quatre - quatre personnalités, quatre parcours
              artistiques, une vision commune. Le studio est né de notre volonté
              de travailler la fleur et le végétal autrement et de l’intégrer à
              nos pratiques artistiques respectives. Notre travail naît de ce
              dialogue à plusieurs voix, où s’entrelacent dramaturgie, art,
              design et botanique.
            </p>
          </div>

          <figure class="relative w-full">
            <!-- Ratio sur mobile, hauteur généreuse dès md -->
            <div
              class="w-full aspect-[16/9] md:aspect-auto md:h-[70vh] lg:h-[78vh] overflow-hidden shadow-lg"
            >
              <img
                src="/static/studio/groupe.jpeg"
                alt="L’équipe Rhizom — quatre personnalités, quatre parcours, une vision commune."
                loading="lazy"
                class="w-full h-full object-cover"
              />
            </div>
          </figure>
        </div>
      </div>
    </section>

    <!-- Démarche artistique : hero side-by-side majestueux -->
    <section id="esprits" class="snap-start min-h-screen bg-white">
      <div class="mx-auto max-w-[1600px] px-6 lg:px-12 md:py-20 flex">
        <div
          class="w-full grid grid-cols-1 md:grid-cols-2 items-center gap-y-12 gap-x-24"
        >
          <div class="space-y-6 md:space-y-8">
            <h3
              class="uppercase font-bold tracking-tight text-[clamp(2rem,6vw,3rem)] leading-[1.02] md:relative md:left-[10rem] justify-self-end whitespace-nowrap z-10"
            >
              NOTRE DÉMARCHE CRÉATIVE
            </h3>
            <p
              class="text-[clamp(1rem,1.2vw,1.25rem)] leading-relaxed max-w-[42rem] px-4 md:relative md:left-[7rem]"
            >
              Rhizom explore la tension entre naturel et artifice pour créer des
              espaces à la frontière de la fleuristerie et de l’art. <br />
              Chaque projet naît d’une réflexion commune avec notre client et
              d’un dialogue au sein du studio. A partir d’une première
              intention, d’une palette, nous imaginons les lignes de notre
              future composition. Une réflexion se porte sur la matière vivante
              et plastique; végétal, bois, argile, tissu, prennent part à la
              création. Puis sur la technique: sculpture, paysage, suspension,
              bouquet. <br />
              Nous accompagnons particuliers et professionnels dans la curation
              florale de leurs événements. Vous souhaitez fleurir un défilé, un
              lancement, un dîner ou une exposition ? Écrivez-nous.
            </p>
          </div>

          <!-- Image grande : 70–80vh -->
          <div class="w-full">
            <div
              class="relative w-full aspect-[4/5] sm:aspect-[3/4] md:aspect-auto md:h-[72vh] lg:h-[80vh] overflow-hidden shadow rounded"
            >
              <img
                src="/static/studio/photo-studio-page.jpg"
                alt="Notre démarche artistique"
                class="absolute inset-0 w-full h-full object-cover"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Engagement : miroir avec image à gauche -->
    <section id="vivant" class="snap-start min-h-screen bg-white">
      <div class="mx-auto max-w-[1600px] px-6 lg:px-12 md:py-20 flex">
        <div
          class="w-full grid grid-cols-1 md:grid-cols-2 items-center gap-y-12 gap-x-24"
        >
          <!-- Image -->
          <div class="order-2 md:order-1 w-full">
            <div
              class="relative w-full aspect-[4/5] sm:aspect-[3/4] md:aspect-auto md:h-[72vh] lg:h-[80vh] overflow-hidden shadow rounded"
            >
              <img
                src="/static/studio/photo-studio-page.jpg"
                alt="Fleur locale"
                class="absolute inset-0 w-full h-full object-cover"
              />
            </div>
          </div>

          <!-- Texte -->
          <div class="order-1 md:order-2 space-y-6 md:space-y-8">
            <h3
              class="uppercase font-bold tracking-tight text-[clamp(1rem,6vw,3rem)] leading-[1.02] md:relative md:left-[-10rem] whitespace-nowrap"
            >
              NOTRE ENGAGEMENT
            </h3>
            <p
              class="text-[clamp(1rem,1.2vw,1.25rem)] leading-relaxed max-w-[42rem]"
            >
              Nous travaillons le végétal avec attention, en choisissant chaque
              variété pour sa présence, sa tenue et sa capacité à dialoguer avec
              l’espace. Quand la saison le permet, nous collaborons avec des
              fermes florales locales, engagées dans une culture respectueuse du
              vivant. Le reste de l'année, nous sélectionnons des fleurs
              durables et singulières, en accord avec l'esprit de chaque projet.
            </p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
/* Gardé simple, rien à changer côté script pour l’instant */
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
