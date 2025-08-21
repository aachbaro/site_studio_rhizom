<template>
  <section class="bg-white">
    <div
      class="max-w-screen-xl mx-auto px-6 md:px-12 pt-16 pb-0 flex flex-col items-start"
    >
      <!-- 1) Intro -->
      <div class="w-full sm:max-w-[34rem] md:max-w-[40rem] text-left mb-8">
        <p class="text-base md:text-lg leading-relaxed">Ecrivez-nous.</p>
      </div>

      <!-- 2) Formulaire : colonne plus étroite pour garder un grand blanc à droite -->
      <form
        class="w-full sm:max-w-[28rem] md:max-w-[34rem] lg:max-w-[38rem] grid grid-cols-1 gap-4 md:gap-5"
        @submit.prevent="onSubmit"
      >
        <!-- nom -->
        <label class="sr-only" for="nom">Nom</label>
        <input
          id="nom"
          type="text"
          name="nom"
          placeholder="nom"
          v-model="nom"
          required
          autocomplete="name"
          class="w-full border border-black rounded-full px-6 py-2 placeholder-black/50 transition-all duration-200 hover:placeholder-transparent focus:placeholder-transparent hover:ring-2 focus:ring-2 ring-black ring-offset-0 focus:outline-none"
        />

        <!-- email -->
        <label class="sr-only" for="email">E-mail</label>
        <input
          id="email"
          type="email"
          name="email"
          placeholder="e-mail"
          v-model="email"
          required
          autocomplete="email"
          class="w-full border border-black rounded-full px-6 py-2 placeholder-black/50 transition-all duration-200 hover:placeholder-transparent focus:placeholder-transparent hover:ring-2 focus:ring-2 ring-black ring-offset-0 focus:outline-none"
        />

        <!-- objet -->
        <label class="sr-only" for="objet">Objet</label>
        <input
          id="objet"
          type="text"
          name="objet"
          placeholder="objet"
          v-model="objet"
          required
          class="w-full border border-black rounded-full px-6 py-2 placeholder-black/50 transition-all duration-200 hover:placeholder-transparent focus:placeholder-transparent hover:ring-2 focus:ring-2 ring-black ring-offset-0 focus:outline-none"
        />

        <!-- message : forme plus carrée (moins large grâce au max-w du form + hauteur généreuse) -->
        <label class="sr-only" for="message">Message</label>
        <textarea
          id="message"
          name="message"
          placeholder="message"
          v-model="message"
          required
          class="w-full h-48 md:h-56 border border-black rounded-2xl px-6 py-4 placeholder-black/50 resize-none transition-all duration-200 hover:placeholder-transparent focus:placeholder-transparent hover:ring-2 focus:ring-2 ring-black ring-offset-0 focus:outline-none"
        ></textarea>

        <!-- bouton à droite -->
        <div class="mt-2">
          <button
            type="submit"
            class="ml-auto block border border-black rounded-full px-8 py-2 text-sm lowercase hover:bg-black hover:text-white transition-colors"
          >
            envoyer
          </button>
        </div>

        <!-- messages -->
        <p v-if="success" class="text-green-600">Message envoyé !</p>
        <p v-if="error" class="text-red-600">{{ error }}</p>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref } from "vue";
import { sendContact } from "../../services/api";

const nom = ref("");
const email = ref("");
const objet = ref("");
const message = ref("");
const success = ref(false);
const error = ref("");

async function onSubmit() {
  success.value = false;
  error.value = "";
  try {
    const res = await sendContact({
      name: nom.value,
      email: email.value,
      objet: objet.value,
      message: message.value,
    });
    if (res && res.error) {
      error.value = res.error;
      return;
    }
    success.value = true;
    nom.value = "";
    email.value = "";
    objet.value = "";
    message.value = "";
  } catch (e) {
    error.value = e.message || "Une erreur est survenue";
  }
}
</script>

<style scoped>
/* rien ici, tout est fait avec Tailwind */
</style>
