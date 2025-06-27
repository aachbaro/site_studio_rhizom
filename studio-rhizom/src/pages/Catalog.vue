<template>
  <div>
    <h1>Catalogue</h1>
    <CatalogItem v-for="c in catalog" :key="c.name" :item="c" />
  </div>
</template>

<script>
import CatalogItem from '../components/CatalogItem.vue'
import catalog from '../../public/data/catalog.json'

export default {
  name: 'CatalogPage',
  components: { CatalogItem },
  data() {
    return { catalog }
  }
}
</script>

<style scoped>
</style>
