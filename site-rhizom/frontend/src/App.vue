<template>
  <!-- Splash vidéo -->
  <SplashVideo
    v-if="showSplash"
    src="/static/home/intro.mp4"
    @ended="onSplashEnded"
  />

  <!-- Contenu normal -->
  <div v-else>
    <CustomCursor />
    <DefaultLayout>
      <router-view />
    </DefaultLayout>
  </div>
</template>

<script>
import DefaultLayout from "./layouts/DefaultLayout.vue";
import CustomCursor from "./components/CustomCursor.vue";
import SplashVideo from "./components/SplashVideo.vue";

export default {
  name: "App",
  components: {
    DefaultLayout,
    CustomCursor,
    SplashVideo,
  },
  data() {
    return {
      // On affiche la splash si jamais l'utilisateur ne l'a pas encore vue
      showSplash: localStorage.getItem("hasSeenSplash") !== "true",
    };
  },
  mounted() {
    // Si on affiche la splash, on bloque le scroll
    if (this.showSplash) {
      document.body.style.overflow = "hidden";
    }
  },
  methods: {
    onSplashEnded() {
      // Quand la vidéo signale qu'elle est terminée
      this.showSplash = false;
      localStorage.setItem("hasSeenSplash", "true");
      document.body.style.overflow = ""; // remet le scroll
    },
  },
};
</script>
