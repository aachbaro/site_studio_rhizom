<template>
  <form @submit.prevent="onSubmit" class="contact-form">
    <input v-model="name" type="text" placeholder="Nom" required class="input" />
    <input v-model="email" type="email" placeholder="E-mail" required class="input" />
    <textarea v-model="message" placeholder="Message" required class="textarea"></textarea>
    <button type="submit" :disabled="loading" class="submit-btn">Envoyer</button>
    <p v-if="success" class="success-msg">Votre message a été envoyé.</p>
    <p v-if="error" class="error-msg">{{ error }}</p>
  </form>
</template>

<script>
import { sendContact } from '@/services/api';

export default {
  name: 'ContactForm',
  data() {
    return {
      name: '',
      email: '',
      message: '',
      loading: false,
      success: false,
      error: ''
    };
  },
  methods: {
    async onSubmit() {
      this.loading = true;
      this.error = '';
      this.success = false;
      try {
        await sendContact({ name: this.name, email: this.email, message: this.message });
        this.success = true;
        this.name = '';
        this.email = '';
        this.message = '';
      } catch (err) {
        this.error = err.message || 'Une erreur est survenue';
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>

<style scoped>
.contact-form {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.input,
.textarea {
  border: 1px solid #000;
  padding: 0.5rem;
  border-radius: 4px;
}
.submit-btn {
  border: 1px solid #000;
  padding: 0.5rem 1rem;
  background: #fff;
  cursor: pointer;
}
.success-msg {
  color: green;
}
.error-msg {
  color: red;
}
</style>
