<template>
  <img
    :src="logoUrl"
    alt="Studio Rhizom"
    class="w-[min(60vw,480px)] md:w-[420px] /* taille */ brightness-0 invert /* passe en blanc si source noire */"
  />
</template>

<script setup>
const logoUrl = "/static/logo/new_studio-logo.svg";
</script>
