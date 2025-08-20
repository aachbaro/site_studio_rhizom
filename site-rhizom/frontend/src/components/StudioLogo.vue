<!-- src/components/StudioLogo.vue -->
<template>
  <img
    :src="logoUrl"
    alt="Studio Rhizom"
    class="w-56 max-w-[60vw] opacity-0 animate-[fadein_0.7s_ease_0.3s_forwards]"
  />
</template>

<script setup>
const logoUrl = "/static/logo/studio-logo.svg";
</script>

<style>
@keyframes fadein {
  to {
    opacity: 1;
  }
}
</style>
