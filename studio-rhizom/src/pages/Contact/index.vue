<template>
  <section class="py-16 bg-white">
    <div class="max-w-screen-lg mx-auto px-4 flex flex-col items-center">
      <!-- 1. Intro texte -->
      <div class="w-full md:w-3/4 text-center mb-12">
        <p class="text-lg leading-relaxed">
          Une envie, un projet, une question ?<br />
          Parlez-nous de votre idée,<br />
          nous serons là pour l’écouter et la faire fleurir.
        </p>
      </div>

      <!-- 2. Formulaire -->
      <form
        class="w-full md:w-3/4 grid grid-cols-1 gap-6"
        @submit.prevent="onSubmit"
      >
        <input
          type="text"
          name="nom"
          placeholder="Nom"
          v-model="nom"
          required
          class="w-full border border-black rounded-full px-6 py-3 placeholder-black/50 focus:outline-none"
        />
        <input
          type="email"
          name="email"
          placeholder="E-mail"
          v-model="email"
          required
          class="w-full border border-black rounded-full px-6 py-3 placeholder-black/50 focus:outline-none"
        />
        <input
          type="text"
          name="objet"
          placeholder="Objet"
          v-model="objet"
          required
          class="w-full border border-black rounded-full px-6 py-3 placeholder-black/50 focus:outline-none"
        />
        <textarea
          name="message"
          rows="8"
          placeholder="Message"
          v-model="message"
          required
          class="w-full border border-black rounded-2xl px-6 py-4 placeholder-black/50 focus:outline-none resize-none"
        ></textarea>
        <button
          type="submit"
          class="self-start border border-black rounded-full px-8 py-2 text-sm uppercase hover:bg-black hover:text-white transition"
        >
          envoyer
        </button>
        <p v-if="success" class="text-green-600">Message envoyé !</p>
        <p v-if="error" class="text-red-600">{{ error }}</p>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { sendContact } from '../../services/api'

const nom = ref('')
const email = ref('')
const objet = ref('')
const message = ref('')
const success = ref(false)
const error = ref('')

async function onSubmit() {
  success.value = false
  error.value = ''
  try {
    await sendContact({
      name: nom.value,
      email: email.value,
      objet: objet.value,
      message: message.value
    })
    success.value = true
    nom.value = ''
    email.value = ''
    objet.value = ''
    message.value = ''
  } catch (e) {
    error.value = e.message || 'Une erreur est survenue'
  }
}
</script>

<style scoped>
/* Aucun style supplémentaire pour l’instant */
</style>
