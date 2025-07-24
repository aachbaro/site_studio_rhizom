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
      <button
        @click="logout"
        class="btn mb-4"
        style="background: #bbb; color: #222"
      >
        Déconnexion
      </button>
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
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
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
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
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

const API_URL = "http://localhost:3001"; // à adapter si besoin

const password = ref("");
const isAuthenticated = ref(false);
const loginError = ref("");

const file = ref(null);
const title = ref("");
const type = ref("carousel");
const uploadStatus = ref("");

const images = ref([]);
const projets = ref([]);

function getToken() {
  return localStorage.getItem("adminToken");
}

function setToken(token) {
  localStorage.setItem("adminToken", token);
}
function removeToken() {
  localStorage.removeItem("adminToken");
}

const handleLogin = async () => {
  loginError.value = "";
  try {
    const res = await fetch(`${API_URL}/api/login.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ password: password.value }),
    });
    if (!res.ok) {
      const data = await res.json();
      loginError.value = data.error || "Erreur de connexion";
      return;
    }
    const data = await res.json();
    setToken(data.token);
    isAuthenticated.value = true;
    password.value = "";
    // Optionnel : recharger les données admin après connexion
    await fetchImages();
    await fetchProjets();
  } catch (err) {
    loginError.value = "Erreur réseau";
  }
};

const logout = () => {
  removeToken();
  isAuthenticated.value = false;
  password.value = "";
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

  let endpoint;
  if (type.value === "carousel") {
    endpoint = `${API_URL}/api/carousel.php`;
  } else {
    endpoint = `${API_URL}/api/projects.php`;
  }

  try {
    const res = await fetch(endpoint, {
      method: "POST",
      headers: {
        Authorization: "Bearer " + getToken(),
      },
      body: formData,
    });
    if (res.ok) {
      uploadStatus.value = "Upload réussi !";
      file.value = null;
      title.value = "";
      // Actualise la liste
      await fetchImages();
      await fetchProjets();
    } else if (res.status === 401 || res.status === 403) {
      logout();
      uploadStatus.value = "Session expirée. Veuillez vous reconnecter.";
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
  const res = await fetch(`${API_URL}/api/carousel/${id}`, {
    method: "DELETE",
    headers: { Authorization: "Bearer " + getToken() },
  });
  if (res.ok) {
    images.value = images.value.filter((img) => img.id !== id);
  } else if (res.status === 401 || res.status === 403) {
    logout();
    alert("Session expirée. Veuillez vous reconnecter.");
  } else {
    alert("Erreur lors de la suppression !");
  }
};

const deleteProjet = async (id) => {
  if (!confirm("Supprimer ce projet ?")) return;
  const res = await fetch(`${API_URL}/api/projects/${id}`, {
    method: "DELETE",
    headers: { Authorization: "Bearer " + getToken() },
  });
  if (res.ok) {
    projets.value = projets.value.filter((p) => p.id !== id);
  } else if (res.status === 401 || res.status === 403) {
    logout();
    alert("Session expirée. Veuillez vous reconnecter.");
  } else {
    alert("Erreur lors de la suppression !");
  }
};

const fetchImages = async () => {
  const res = await fetch(`${API_URL}/api/carousel`);
  images.value = await res.json();
};
const fetchProjets = async () => {
  const res = await fetch(`${API_URL}/api/projects`);
  projets.value = await res.json();
};

onMounted(() => {
  isAuthenticated.value = !!getToken();
  fetchImages();
  fetchProjets();
});
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
