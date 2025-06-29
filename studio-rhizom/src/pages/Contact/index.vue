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
        action="mailto:contact@studiorhizom.com"
        method="POST"
        enctype="text/plain"
      >
        <input
          type="text"
          name="nom"
          placeholder="Nom"
          required
          class="w-full border border-black rounded-full px-6 py-3 placeholder-black/50 focus:outline-none"
        />
        <input
          type="email"
          name="email"
          placeholder="E-mail"
          required
          class="w-full border border-black rounded-full px-6 py-3 placeholder-black/50 focus:outline-none"
        />
        <input
          type="text"
          name="objet"
          placeholder="Objet"
          required
          class="w-full border border-black rounded-full px-6 py-3 placeholder-black/50 focus:outline-none"
        />
        <textarea
          name="message"
          rows="8"
          placeholder="Message"
          required
          class="w-full border border-black rounded-2xl px-6 py-4 placeholder-black/50 focus:outline-none resize-none"
        ></textarea>
        <button
          type="submit"
          class="self-start border border-black rounded-full px-8 py-2 text-sm uppercase hover:bg-black hover:text-white transition"
        >
          envoyer
        </button>
      </form>
    </div>
  </section>
</template>

<script setup>
// Pas de logique JavaScript nécessaire pour un envoi mailto.
// Si tu veux un vrai back-end ou Formspree, il suffit de remplacer l'attribut `action`.
</script>

<style scoped>
/* Aucun style supplémentaire pour l’instant */
</style>
