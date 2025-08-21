<template>
  <div
    class="snap-y snap-mandatory h-screen overflow-y-scroll scroll-smooth hide-scrollbar"
  >
    <section id="team" class="min-h-screen snap-start bg-white mb-[2rem] py-14">
      <!-- 1. Intro text -->
      <div class="max-w-screen-xl px-1 mx-auto">
        <p class="text-lg md:text-1xl leading-relaxed mb-8 max-w-4xl pl-9">
          Nous sommes quatre — quatre personnalités, quatre parcours, une vision
          commune.<br />
          Notre travail naît de ce dialogue à plusieurs voix, où s’entrelacent
          art, design et sens du détail.<br />
          À travers chaque projet, nous cherchons à créer des formes justes,
          sensibles, en lien avec l’espace et le vivant.
        </p>
      </div>
      <!-- Carrousel d’équipe -->
      <!-- <div class="w-full mb-8">
        <TeamCarousel :members="team" />
      </div> -->
      <!-- Image d’équipe -->
      <div class="max-w-screen-xl px-1 mx-auto mt-8">
        <figure class="relative w-full">
          <!-- ratio 16:9 sur mobile, 21:9 à partir de md pour effet “pano” -->
          <div
            class="w-full aspect-[16/9]  overflow-hidden shadow-lg"
          >
            <img
              src="/static/studio/groupe.jpeg"
              alt="L’équipe Rhizom — quatre personnalités, quatre parcours, une vision commune."
              loading="lazy"
              class="w-full h-full object-cover"
            />
          </div>
          <!-- (Optionnel) légende -->
          <!-- <figcaption class="mt-3 text-sm text-gray-500 pl-1">
      L’équipe Rhizom
    </figcaption> -->
        </figure>
      </div>
    </section>

    <!-- 3. Quatre esprits. Un seul but. -->
    <!-- Bloc 1 -->
    <section id="esprits" class="h-screen snap-start bg-white">
      <div class="mx-auto px-4 grid md:grid-cols-2 items-center h-screen">
        <div>
          <h3
            class="text-3xl md:text-4xl mb-[10rem] uppercase font-bold md:relative md:left-[10rem] justify-self-end whitespace-nowrap"
          >
            Notre démarche artistique
          </h3>

          <p
            class="text-xl md:text-2xl leading-relaxed max-w-3xl justify-self-end px-4"
          >
            Nous proposons un service global de direction artistique et de
            production événementielle, pensé comme une extension de cette
            approche.<br />
            Nous imaginons des univers sur mesure : choix du lieu, scénographie,
            compositions florales, temps forts…<br />
            De la première idée à la dernière lumière, nous orchestrons chaque
            étape avec précision et intuition.
          </p>
        </div>
        <div class="h-full w-full flex items-stretch">
          <img
            src="/static/studio/photo-studio-page.jpg"
            alt="Notre démarche artistique"
            class="w-full h-full object-cover shadow h-screen"
            style="min-height: 300px"
          />
        </div>
      </div>
    </section>

    <!-- Bloc 2 -->
    <section id="vivant" class="h-screen snap-start bg-white">
      <div class="mx-auto grid md:grid-cols-2 items-center h-screen">
        <div class="h-full w-full flex items-stretch">
          <img
            src="/static/studio/photo-studio-page.jpg"
            alt=""
            class="w-full h-full object-cover shadow h-screen"
            style="min-height: 300px"
          />
        </div>
        <div>
          <h3
            class="text-3xl md:text-4xl mb-[10rem] uppercase font-bold md:relative md:left-[-10rem] whitespace-nowrap"
          >
            Notre engagement, la fleur locale
          </h3>
          <p class="text-xl md:text-2xl leading-relaxed max-w-3xl px-4">
            Nous travaillons le végétal avec attention, en choisissant chaque
            variété pour sa présence, sa tenue et sa capacité à dialoguer avec
            l’espace.<br />
            Dès que la saison le permet, nous collaborons avec des fermes
            florales locales, engagées dans une culture respectueuse du
            vivant.<br />
            Le reste de l’année, nous sélectionnons des fleurs durables et
            singulières, en accord avec l’esprit de chaque projet.
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import TeamCarousel from "../../components/TeamCarousel.vue";

const team = ref([]);

onMounted(async () => {
  const res = await fetch("/data/team.json");
  team.value = await res.json();
});
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none; /* IE/Edge */
  scrollbar-width: none; /* Firefox */
}
</style>
