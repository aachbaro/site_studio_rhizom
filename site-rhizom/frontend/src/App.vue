<template>
  <CustomCursor />
  <SplashVideo
    v-if="showSplash"
    :src="SPLASH_VIDEO"
    :minSkipDelayMs="5000"
    :maxDurationMs="10000"
    @exit="onSplashExit"
    >
    <!-- :poster="SPLASH_POSTER" -->
    <template #logo>
      <StudioLogo />
    </template>
  </SplashVideo>

  <div v-else>
    <DefaultLayout>
      <router-view />
    </DefaultLayout>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import DefaultLayout from "./layouts/DefaultLayout.vue";
import SplashVideo from "./components/SplashVideo.vue";
import StudioLogo from "./components/StudioLogo.vue";
import CustomCursor from "./components/CustomCursor.vue";

const SPLASH_VIDEO = "/static/home/intro.mp4";
// const SPLASH_POSTER = "/static/home/intro_poster.jpg";

// Ajoute ?splash=1 dans l'URL pour forcer le splash même après le premier passage.
const force = new URLSearchParams(location.search).get("splash") === "1";
const showSplash = ref(
  force || localStorage.getItem("hasSeenSplash") !== "true"
);

onMounted(() => {
  if (showSplash.value) document.body.style.overflow = "hidden";
});
onBeforeUnmount(() => {
  document.body.style.overflow = "";
});

function onSplashExit() {
  localStorage.setItem("hasSeenSplash", "true");
  showSplash.value = false;
  document.body.style.overflow = "";
}
</script>

<!-- <template>
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
 -->
