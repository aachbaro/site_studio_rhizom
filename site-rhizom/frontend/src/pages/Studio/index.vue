<template>
  <div
    class="snap-container w-full lg:snap-y lg:snap-mandatory lg:h-screen lg:overflow-y-scroll lg:scroll-smooth hide-scrollbar"
  >
    <!-- SECTION 1 -->
    <section
      id="team"
      class="bg-white lg:min-h-[100svh] lg:snap-start py-8 lg:py-0"
    >
      <div class="w-full lg:px-0">
        <div
          class="grid grid-cols-1 lg:grid-cols-12 lg:h-[100svh] lg:gap-x-10 xl:gap-x-12"
        >
          <!-- Image pleine hauteur -->
          <figure class="order-1 lg:order-1 lg:col-span-7 flex">
            <img
              src="/static/studio/groupe.jpeg"
              alt="L’équipe Rhizom — quatre personnalités, quatre parcours, une vision commune."
              class="w-full h-auto lg:h-full object-cover"
              loading="lazy"
            />
          </figure>

          <!-- Texte aligné bas -->
          <div class="order-2 lg:order-2 lg:col-span-5 flex">
            <div
              class="px-4 lg:px-0 w-full max-w-[45ch] lg:pr-10 lg:self-end lg:pb-16 xl:pb-32 mt-6 lg:mt-0"
            >
              <h2
                class="font-urw font-black uppercase tracking-tight text-[clamp(1.5rem,4.5vw,2.5rem)] leading-[1.02] mb-3 lg:mb-5"
              >
                LE STUDIO
              </h2>
              <p
                class="font-real text-[clamp(1rem,1.05vw,1.125rem)] leading-[1.45]"
              >
                Nous sommes quatre – quatre personnalités, quatre parcours
                artistiques, une vision commune. Le studio est né de notre
                volonté de travailler la fleur et le végétal autrement et de
                l’intégrer à nos pratiques artistiques respectives. Notre
                travail naît de ce dialogue à plusieurs voix, où s’entrelacent
                dramaturgie, art, design et botanique.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 2 : DÉMARCHE (inchangée) -->
    <section
      id="demarche"
      class="bg-white py-8 lg:py-14 xl:py-16 lg:min-h-[100svh] lg:snap-start"
    >
      <figure
        class="relative w-full aspect-[4/5] lg:aspect-auto lg:h-[calc(100svh-7rem)] xl:h-[calc(100svh-8rem)]"
      >
        <img
          src="/static/home/Carousel/Carou7.png"
          alt="Notre démarche créative"
          class="absolute inset-0 w-full h-full object-cover object-center"
          loading="lazy"
        />

        <!-- Overlay (desktop/portrait) -->
        <div
          class="not-landscape absolute inset-0 flex justify-center lg:justify-start"
        >
          <div
            class="self-start lg:self-center px-4 lg:px-16 xl:px-24 pt-2 lg:pt-10 max-w-[52ch] text-white mx-auto lg:mx-0 text-left"
          >
            <h3
              class="font-urw font-black uppercase tracking-tight text-[clamp(1.5rem,4.5vw,3rem)] leading-tight mb-4"
            >
              NOTRE DÉMARCHE CRÉATIVE
            </h3>
            <p
              class="font-real text-[clamp(1rem,1.15vw,1.125rem)] leading-[1.5]"
            >
              Rhizom explore la tension entre naturel et artifice pour créer des
              espaces à la frontière de la fleuristerie et de l’art. Chaque
              projet naît d’une réflexion commune avec notre client et d’un
              dialogue au sein du studio. À partir d’une première intention,
              d’une palette, nous imaginons les lignes de notre future
              composition. Une réflexion se porte sur la matière vivante et
              plastique ; végétal, bois, argile, tissu, prennent part à la
              création. Puis sur la technique : sculpture, paysage, suspension,
              bouquet. <br class="hidden lg:block" />
              Nous accompagnons particuliers et professionnels dans la curation
              florale de leurs événements. Vous souhaitez fleurir un défilé, un
              lancement, un dîner ou une exposition ? Écrivez-nous.
            </p>
          </div>
        </div>
      </figure>

      <!-- Texte alternatif (mobile paysage) -->
      <div class="only-landscape px-4 mt-4 max-w-[52ch] mx-auto text-left">
        <h3
          class="font-urw font-black uppercase tracking-tight text-[clamp(1.25rem,4.5vw,2rem)] leading-tight mb-3 text-black"
        >
          NOTRE DÉMARCHE CRÉATIVE
        </h3>
        <p
          class="font-real text-[clamp(0.95rem,1.1vw,1.05rem)] leading-[1.5] text-black"
        >
          Rhizom explore la tension entre naturel et artifice pour créer des
          espaces à la frontière de la fleuristerie et de l’art. Chaque projet
          naît d’une réflexion commune avec notre client et d’un dialogue au
          sein du studio. À partir d’une première intention, d’une palette, nous
          imaginons les lignes de notre future composition. Une réflexion se
          porte sur la matière vivante et plastique ; végétal, bois, argile,
          tissu, prennent part à la création. Puis sur la technique : sculpture,
          paysage, suspension, bouquet. Nous accompagnons particuliers et
          professionnels dans la curation florale de leurs événements. Vous
          souhaitez fleurir un défilé, un lancement, un dîner ou une exposition
          ? Écrivez-nous.
        </p>
      </div>
    </section>

    <!-- SECTION 3 : ENGAGEMENT -->
    <section
      id="engagement"
      class="bg-white py-8 lg:py-14 xl:py-16 lg:min-h-[100svh] lg:snap-start"
    >
      <div class="w-full lg:px-0">
        <div
          class="grid grid-cols-1 lg:grid-cols-12 lg:gap-x-10 xl:gap-x-12 lg:h-[calc(100svh-7rem)] xl:h-[calc(100svh-8rem)]"
        >
          <!-- Image centrée verticalement -->
          <figure class="order-1 lg:order-1 lg:col-span-7 flex items-center">
            <img
              src="/static/studio/mains-projets.jpg"
              alt="Fleur locale"
              class="w-full h-auto lg:max-h-full object-cover object-center"
              loading="lazy"
            />
          </figure>

          <!-- Texte aligné haut avec l’image -->
          <div
            class="order-2 lg:order-2 lg:col-span-5 flex items-start lg:pt-10"
          >
            <div class="px-4 lg:px-0 w-full max-w-[48ch] lg:pr-10 mt-6 lg:mt-0">
              <h3
                class="font-urw font-black uppercase tracking-tight text-[clamp(1.5rem,4.5vw,2.5rem)] leading-[1.02] mb-4"
              >
                NOTRE ENGAGEMENT
              </h3>
              <p
                class="font-real text-[clamp(1rem,1.1vw,1.125rem)] leading-[1.5]"
              >
                Nous travaillons le végétal avec attention, en choisissant
                chaque variété pour sa présence, sa tenue et sa capacité à
                dialoguer avec l’espace. Quand la saison le permet, nous
                collaborons avec des fermes florales locales, engagées dans une
                culture respectueuse du vivant. Le reste de l'année, nous
                sélectionnons des fleurs durables et singulières, en accord avec
                l'esprit de chaque projet.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
// rien à changer côté script
</script>

<style scoped>
/* Masquer la scrollbar visuelle */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Par défaut : on affiche la version overlay, pas la version paysage */
.only-landscape {
  display: none;
}
.not-landscape {
  display: block;
}

/* Règles spécifiques au MOBILE PAYSAGE (largeur <= 1023px) */
@media (orientation: landscape) and (max-width: 1023px) {
  /* Désactiver complètement le scroll-snap en paysage mobile */
  .snap-container {
    scroll-snap-type: none !important;
    height: auto !important;
    overflow: visible !important;
  }
  /* Section 2 : cacher l'overlay et montrer le texte sous l'image, en noir */
  .not-landscape {
    display: none !important;
  }
  .only-landscape {
    display: block !important;
  }
}
</style>
