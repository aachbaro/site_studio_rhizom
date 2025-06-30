<template>
  <div class="p-6">
    <h1 class="text-2xl mb-4">Administration des projets</h1>
    <form @submit.prevent="submit" class="space-y-2 mb-8">
      <input v-model="title" placeholder="Titre" class="border px-2 py-1 w-full" />
      <textarea v-model="description" placeholder="Description" class="border px-2 py-1 w-full" />
      <input type="file" @change="onFile" />
      <button type="submit" class="px-4 py-1 border">
        {{ editingId ? 'Mettre à jour' : 'Créer' }}
      </button>
      <p v-if="success" class="text-green-600">Opération réussie</p>
      <p v-if="error" class="text-red-600">{{ error }}</p>
    </form>

    <table class="w-full border" v-if="projects.length">
      <thead>
        <tr>
          <th class="border p-2">Titre</th>
          <th class="border p-2">Description</th>
          <th class="border p-2">Image</th>
          <th class="border p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in projects" :key="p.id">
          <td class="border p-2">{{ p.title }}</td>
          <td class="border p-2">{{ p.description }}</td>
          <td class="border p-2">
            <img v-if="p.imagePath" :src="`/api/${p.imagePath}`" class="h-16" />
          </td>
          <td class="border p-2 space-x-2">
            <button @click="edit(p)" class="px-2 border">Editer</button>
            <button @click="removeProject(p.id)" class="px-2 border">Supprimer</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getAll, create, update, remove } from '../services/projects'

const auth = { username: 'admin', password: 'motDePasseAdmin' }

const projects = ref([])
const title = ref('')
const description = ref('')
const file = ref(null)
const editingId = ref(null)
const success = ref(false)
const error = ref('')

onMounted(load)

function onFile(e) {
  file.value = e.target.files[0]
}

async function load() {
  projects.value = await getAll(auth)
}

async function submit() {
  const form = new FormData()
  form.append('title', title.value)
  form.append('description', description.value)
  if (file.value) form.append('image', file.value)

  try {
    if (editingId.value) {
      await update(editingId.value, form, auth)
    } else {
      await create(form, auth)
    }
    success.value = true
    error.value = ''
    title.value = ''
    description.value = ''
    file.value = null
    editingId.value = null
    await load()
  } catch (e) {
    success.value = false
    error.value = e.message || 'Erreur'
  }
}

function edit(p) {
  editingId.value = p.id
  title.value = p.title
  description.value = p.description
  file.value = null
}

async function removeProject(id) {
  if (!confirm('Confirmer la suppression ?')) return
  await remove(id, auth)
  await load()
}
</script>

<style scoped>
/* aucun style particulier */
</style>

