<template>
  <CustomCursor />
  <SplashVideo
    v-if="showSplash"
    src="/static/home/intro.mp4"
    @ended="onSplashEnded"
  />
  <div v-else>
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
  components: { DefaultLayout, CustomCursor, SplashVideo },
  data() {
    return {
      showSplash: localStorage.getItem("hasSeenSplash") !== "true",
    };
  },
  mounted() {
    if (this.showSplash) {
      // Splash active : on bloque le scroll
      document.body.classList.add("splash-active");
      document.body.style.overflow = "hidden";
    } else {
      // Splash déjà vue : pas de blocage
      document.body.classList.remove("splash-active");
      document.body.style.overflow = "";
    }
  },
  methods: {
    onSplashEnded() {
      this.showSplash = false;
      localStorage.setItem("hasSeenSplash", "true");
      // Retire la classe et rétablit le scroll
      document.body.classList.remove("splash-active");
      document.body.style.overflow = "";
    },
  },
};
</script>
