<template>
  <div>
    <PageTitle>Contact</PageTitle>
    <ContactForm />
  </div>
</template>

<script>
import ContactForm from '../../components/ContactForm.vue'
import PageTitle from '../../components/PageTitle.vue'

export default {
  name: 'ContactPage',
  components: { ContactForm, PageTitle }
}
</script>

<style scoped>
</style>
