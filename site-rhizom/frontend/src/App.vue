<template>
  <!-- 1) Le site est MONTÉ dès le début -->
  <CustomCursor v-if="isDesktop" />

  <Header />

  <DefaultLayout>
    <router-view />
  </DefaultLayout>

  <!-- 2) Le splash est un OVERLAY au-dessus, masqué quand on sort -->
  <SplashVideo
    v-show="showSplash"
    :src="SPLASH_VIDEO"
    :minSkipDelayMs="5000"
    :maxDurationMs="10000"
    @exit="onSplashExit"
  >
    <template #logo>
      <StudioLogo class="logo-splash" />
    </template>
  </SplashVideo>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import DefaultLayout from "./layouts/DefaultLayout.vue";
import SplashVideo from "./components/SplashVideo.vue";
import StudioLogo from "./components/StudioLogo.vue";
import CustomCursor from "./components/CustomCursor.vue";
import Header from "./components/Header.vue";

const isDesktop = ref(false);
let mq;

const SPLASH_VIDEO = "/static/home/intro.mp4";
// pas de poster pour l’instant

// Force via ?splash=1
const force = new URLSearchParams(location.search).get("splash") === "1";
// Toutes les 15 minutes :
// const SPLASH_INTERVAL = 15 * 60 * 1000;
const SPLASH_INTERVAL = 0;

const lastSplash = parseInt(localStorage.getItem("lastSplashTime") || "0", 10);
const now = Date.now();
const showSplash = ref(
  force || lastSplash === 0 || now - lastSplash >= SPLASH_INTERVAL
);

function updateIsDesktop() {
  isDesktop.value = mq.matches;
}

onMounted(() => {
  if (showSplash.value) document.body.style.overflow = "hidden";
  mq = window.matchMedia("(hover: hover) and (pointer: fine)");
  updateIsDesktop();
  mq.addEventListener("change", updateIsDesktop);
});
onBeforeUnmount(() => {
  document.body.style.overflow = "";
  mq?.removeEventListener("change", updateIsDesktop);
});

function onSplashExit() {
  localStorage.setItem("lastSplashTime", String(Date.now()));
  showSplash.value = false;
  document.body.style.overflow = "";
}
</script>

<style>
/* Optionnel : micro-retard & fade du logo */
.logo-splash {
  opacity: 0;
  animation: logoFadeIn 5s ease forwards;
  animation-delay: 3s; /* le logo apparaît plus tard */
}
@keyframes logoFadeIn {
  to {
    opacity: 1;
  }
}
</style>

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
