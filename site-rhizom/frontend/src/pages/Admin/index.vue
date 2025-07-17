<template>
  <div class="admin-container" style="max-width: 400px; margin: 4rem auto">
    <h1>Accès Admin</h1>

    <div v-if="!isAuthenticated">
      <input
        v-model="password"
        type="password"
        placeholder="Mot de passe"
        class="input"
        @keyup.enter="handleLogin"
        style="margin-bottom: 8px"
      />
      <button @click="handleLogin" class="btn">Se connecter</button>
      <p style="color: red" v-if="loginError">{{ loginError }}</p>
    </div>

    <div v-else>
      <h2>Ajouter une image</h2>
      <select v-model="type" class="input" style="margin-bottom: 8px">
        <option value="carousel">Carousel</option>
        <option value="project">Projet</option>
      </select>
      <input
        type="file"
        accept="image/*"
        @change="handleFileChange"
        class="input"
        style="margin-bottom: 8px"
      />
      <input
        v-model="title"
        type="text"
        placeholder="Titre"
        class="input"
        style="margin-bottom: 8px"
      />
      <button @click="handleSubmit" class="btn">Uploader</button>
      <p v-if="uploadStatus" style="margin-top: 12px">{{ uploadStatus }}</p>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="img in images" :key="img.id" class="relative group">
          <img
            :src="img.url"
            :alt="img.title"
            class="rounded shadow w-full h-48 object-cover"
          />
          <button
            @click="deleteImage(img.id)"
            class="absolute top-2 right-2 bg-red-600 text-white px-2 py-1 rounded opacity-80 hover:opacity-100"
          >
            Supprimer
          </button>
          <div class="text-xs text-center mt-1">{{ img.title }}</div>
        </div>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="proj in projets" :key="proj.id" class="relative group">
          <img
            :src="proj.url"
            :alt="proj.title"
            class="rounded shadow w-full h-48 object-cover"
          />
          <button
            @click="deleteProjet(proj.id)"
            class="absolute top-2 right-2 bg-red-600 text-white px-2 py-1 rounded opacity-80 hover:opacity-100"
          >
            Supprimer
          </button>
          <div class="text-xs text-center mt-1">{{ proj.title }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

// Mot de passe "admin" en dur (à améliorer côté backend ensuite)
const password = ref("");
const isAuthenticated = ref(false);
const loginError = ref("");

const file = ref(null);
const title = ref("");
const type = ref("carousel"); // carousel ou project
const uploadStatus = ref("");

const handleLogin = () => {
  // Change "votremotdepasse" par le tien
  if (password.value === "votremotdepasse") {
    isAuthenticated.value = true;
    loginError.value = "";
  } else {
    loginError.value = "Mot de passe incorrect.";
  }
};

const handleFileChange = (event) => {
  file.value = event.target.files[0];
};

const handleSubmit = async () => {
  if (!file.value || !title.value) {
    uploadStatus.value = "Image et titre obligatoires !";
    return;
  }
  const formData = new FormData();
  formData.append("image", file.value);
  formData.append("title", title.value);
  formData.append("type", type.value);

  try {
    const endpoint =
      type.value === "carousel"
        ? "http://localhost:3001/api/carousel"
        : "http://localhost:3001/api/projects";
    const res = await fetch(endpoint, {
      method: "POST",
      body: formData,
    });
    if (res.ok) {
      uploadStatus.value = "Upload réussi !";
      file.value = null;
      title.value = "";
    } else {
      const error = await res.json();
      uploadStatus.value = "Erreur : " + (error.error || "Upload échoué");
    }
  } catch (e) {
    uploadStatus.value = "Erreur réseau : " + e.message;
  }
};

const deleteImage = async (id) => {
  if (!confirm("Supprimer cette image ?")) return;
  const res = await fetch(`/api/carousel/${id}`, { method: "DELETE" });
  if (res.ok) {
    images.value = images.value.filter((img) => img.id !== id);
  } else {
    alert("Erreur lors de la suppression !");
  }
};

const deleteProjet = async (id) => {
  if (!confirm("Supprimer ce projet ?")) return;
  const res = await fetch(`/api/projects/${id}`, { method: "DELETE" });
  if (res.ok) {
    projets.value = projets.value.filter((p) => p.id !== id);
  } else {
    alert("Erreur lors de la suppression !");
  }
};

const images = ref([]);

const fetchImages = async () => {
  const res = await fetch("/api/carousel");
  images.value = await res.json();
};
const projets = ref([]);

const fetchProjets = async () => {
  const res = await fetch("/api/projects");
  projets.value = await res.json();
};

onMounted(fetchImages);
onMounted(fetchProjets);
</script>

<style scoped>
.input {
  display: block;
  width: 100%;
  padding: 8px;
  margin-bottom: 4px;
  border-radius: 4px;
  border: 1px solid #ccc;
}
.btn {
  background: black;
  color: white;
  padding: 8px 16px;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  margin-top: 4px;
}
</style>
