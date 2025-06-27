<template>
  <div class="catalog-item">
    <img :src="item.image" :alt="item.name" />
    <h3>{{ item.name }} - {{ item.price }}€</h3>
    <p>{{ item.description }}</p>
  </div>
</template>

<script>
export default {
  name: 'CatalogItem',
  props: {
    item: Object
  }
}
</script>

<style scoped>
.catalog-item {
  border: 1px solid #eee;
  padding: 1rem;
}
</style>
