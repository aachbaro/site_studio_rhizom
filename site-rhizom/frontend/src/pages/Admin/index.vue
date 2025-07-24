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
import {
  loginAdmin,
  fetchProjects,
  fetchCarousel,
  addProject,
  addCarouselImage,
  deleteProject,
  deleteCarouselImage,
} from "../../services/api.js";

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
    const data = await loginAdmin(password.value);
    if (data.error || !data.token) {
      loginError.value = data.error || "Erreur de connexion";
      return;
    }
    setToken(data.token);
    isAuthenticated.value = true;
    password.value = "";
    // Recharge les listes après login
    await fetchAll();
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
  try {
    let res;
    if (type.value === "carousel") {
      res = await addCarouselImage({
        title: title.value,
        image: file.value,
        token: getToken(),
      });
    } else {
      res = await addProject({
        title: title.value,
        image: file.value,
        token: getToken(),
      });
    }

    if (res.error) {
      if (
        res.error.includes("non autorisé") ||
        res.error.includes("Token invalide") ||
        res.error.includes("expiré")
      ) {
        logout();
        uploadStatus.value = "Session expirée. Veuillez vous reconnecter.";
        return;
      }
      uploadStatus.value = "Erreur : " + res.error;
    } else {
      uploadStatus.value = "Upload réussi !";
      file.value = null;
      title.value = "";
      await fetchAll();
    }
  } catch (e) {
    uploadStatus.value = "Erreur réseau : " + e.message;
  }
};

const deleteImage = async (id) => {
  if (!confirm("Supprimer cette image ?")) return;
  const res = await deleteCarouselImage({ id, token: getToken() });
  if (res && res.success) {
    images.value = images.value.filter((img) => img.id !== id);
  } else if (res && res.error) {
    if (
      res.error.includes("non autorisé") ||
      res.error.includes("Token invalide") ||
      res.error.includes("expiré")
    ) {
      logout();
      alert("Session expirée. Veuillez vous reconnecter.");
    } else {
      alert("Erreur lors de la suppression ! " + res.error);
    }
  } else {
    alert("Erreur lors de la suppression !");
  }
};

const deleteProjet = async (id) => {
  if (!confirm("Supprimer ce projet ?")) return;
  const res = await deleteProject({ id, token: getToken() });
  if (res && res.success) {
    projets.value = projets.value.filter((p) => p.id !== id);
  } else if (res && res.error) {
    if (
      res.error.includes("non autorisé") ||
      res.error.includes("Token invalide") ||
      res.error.includes("expiré")
    ) {
      logout();
      alert("Session expirée. Veuillez vous reconnecter.");
    } else {
      alert("Erreur lors de la suppression ! " + res.error);
    }
  } else {
    alert("Erreur lors de la suppression !");
  }
};

const fetchAll = async () => {
  images.value = await fetchCarousel();
  projets.value = await fetchProjects();
};

onMounted(() => {
  isAuthenticated.value = !!getToken();
  fetchAll();
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
