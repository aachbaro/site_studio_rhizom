<template>
  <div
    class="snap-y snap-mandatory h-screen overflow-y-scroll scroll-smooth hide-scrollbar"
  >
    <section id="team" class="min-h-screen snap-start bg-white mb-[2rem] py-24">
      <!-- 1. Intro text -->
      <div class="max-w-screen-xl px-1 mx-auto pt-24 md:pt-26">
        <p
          class="text-lg md:text-1xl leading-relaxed mb-8 max-w-4xl font-[550]"
        >
          Nous sommes quatre — quatre personnalités, quatre parcours, une vision
          commune.<br />
          Notre travail naît de ce dialogue à plusieurs voix, où s’entrelacent
          art, design et sens du détail.<br />
          À travers chaque projet, nous cherchons à créer des formes justes,
          sensibles, en lien avec l’espace et le vivant.
        </p>
        <h2
          class="text-4xl md:text-5xl leading-tight mb-8 max-w-4xl mx-auto text-right self-start md:pl-16 font-bold"
        >
          Quatre personnalités<br />
          Quatre talents
        </h2>
      </div>
      <!-- Carrousel d’équipe -->
      <div class="w-full">
        <TeamCarousel :members="team" />
      </div>
    </section>

    <!-- 3. Quatre esprits. Un seul but. -->
    <!-- Bloc 1 -->
    <section id="esprits" class="h-screen snap-start bg-white">
      <div
        class="max-w-screen-xl min-h-screen mx-auto px-4 grid md:grid-cols-2 gap-12 items-center"
      >
        <div>
          <h3
            class="text-3xl md:text-4xl mb-[10rem] uppercase font-bold md:relative md:left-[10rem]"
          >
            Notre démarche artistique
          </h3>

          <p class="text-xl md:text-2xl leading-relaxed font-[550]">
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
            alt=""
            class="w-full h-full object-contain shadow"
            style="min-height: 300px"
          />
        </div>
      </div>
    </section>

    <!-- Bloc 2 -->
    <section id="vivant" class="h-screen snap-start bg-white">
      <div
        class="max-w-screen-xl min-h-screen mx-auto px-4 grid md:grid-cols-2 gap-12 items-center"
      >
        <div class="h-full w-full flex items-stretch">
          <img
            src="/static/studio/photo-studio-page.jpg"
            alt=""
            class="w-full h-full object-cover shadow rounded-md"
            style="min-height: 300px"
          />
        </div>
        <div>
          <h3
            class="text-3xl md:text-4xl mb-[10rem] uppercase font-bold md:relative md:left-[-10rem] whitespace-nowrap"
          >
            Notre engagement, la fleur locale
          </h3>
          <p class="text-xl md:text-2xl leading-relaxed font-[550]">
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
