<template>
  <form class="contact-form" @submit.prevent="submit">
    <label>
      Nom
      <input v-model="name" required />
    </label>
    <label>
      Message
      <textarea v-model="message" required></textarea>
    </label>
    <button type="submit">Envoyer</button>
  </form>
</template>

<script>
export default {
  name: 'ContactForm',
  data() {
    return {
      name: '',
      message: ''
    }
  },
  methods: {
    submit() {
      // placeholder submit handler
      alert(`Merci ${this.name}!`)
    }
  }
}
</script>

<style scoped>
.contact-form {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
</style>
