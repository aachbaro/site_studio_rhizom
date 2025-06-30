<template>
  <section class="py-16 bg-white">
    <!-- 1. Intro text -->
    <div class="max-w-screen-lg mx-auto px-4">
      <p class="text-base leading-relaxed mb-6">
        Nous sommes quatre — quatre personnalités, quatre parcours, une vision
        commune.<br />
        Notre travail naît de ce dialogue à plusieurs voix, où s’entrelacent
        art, design et sens du détail.<br />
        À travers chaque projet, nous cherchons à créer des formes justes,
        sensibles, en lien avec l’espace et le vivant.
      </p>
      <h2 class="font-halogen text-4xl md:text-5xl leading-tight mb-8">
        Quatre personnalités.<br />
        Quatre regards.
      </h2>
    </div>

    <!-- Carrousel d’équipe -->
    <div class="max-w-screen-lg mx-auto mt-8 px-4">
      <TeamCarousel :members="team" />
    </div>
  </section>

  <!-- 3. Quatre esprits. Un seul but. -->
  <section class="py-16 bg-white">
    <div class="max-w-screen-lg mx-auto px-4 grid md:grid-cols-2 gap-12">
      <div>
        <h3 class="font-halogen text-3xl md:text-4xl mb-6">
          Quatre esprits. Un seul but.
        </h3>
        <p class="text-base leading-relaxed">
          Nous proposons un service global de direction artistique et de
          production événementielle, pensé comme une extension de cette
          approche.<br />
          Nous imaginons des univers sur mesure : choix du lieu, scénographie,
          compositions florales, temps forts…<br />
          De la première idée à la dernière lumière, nous orchestrons chaque
          étape avec précision et intuition.
        </p>
      </div>
      <!-- <div>
        <img
          src="/images/home/studio/graphic-1.jpg"
          alt=""
          class="w-full h-auto object-cover rounded-md shadow"
        />
      </div> -->
    </div>
  </section>

  <!-- 4. Du vivant. Du local. -->
  <section class="py-16 bg-white">
    <div
      class="max-w-screen-lg mx-auto px-4 grid md:grid-cols-2 gap-12 items-center"
    >
      <!-- <div>
        <img
          src="/images/home/studio/graphic-2.jpg"
          alt=""
          class="w-full h-auto object-cover rounded-md shadow"
        />
      </div> -->
      <div>
        <h3 class="font-halogen text-3xl md:text-4xl mb-6">
          Du vivant. Du local.
        </h3>
        <p class="text-base leading-relaxed">
          Nous travaillons le végétal avec attention, en choisissant chaque
          variété pour sa présence, sa tenue et sa capacité à dialoguer avec
          l’espace.<br />
          Dès que la saison le permet, nous collaborons avec des fermes florales
          locales, engagées dans une culture respectueuse du vivant.<br />
          Le reste de l’année, nous sélectionnons des fleurs durables et
          singulières, en accord avec l’esprit de chaque projet.
        </p>
      </div>
    </div>
  </section>
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
/* si besoin d'ajustements spécifiques */
</style>
