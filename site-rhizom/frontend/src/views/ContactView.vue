<template>
  <section class="contact-page">
    <h1>Contact</h1>
    <ContactForm />
  </section>
</template>

<script>
import ContactForm from '@/components/ContactForm.vue';

export default {
  components: { ContactForm }
};
</script>

<style scoped>
.contact-page {
  padding: 1rem;
}
</style>
