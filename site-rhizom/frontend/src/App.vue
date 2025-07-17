<template>
  <CustomCursor />
  <DefaultLayout>
    <router-view />
  </DefaultLayout>
</template>

<script>
import DefaultLayout from "./layouts/DefaultLayout.vue";
import CustomCursor from "./components/CustomCursor.vue";

export default {
  name: "App",
  components: { DefaultLayout, CustomCursor },
};
</script>
