<template>
  <div class="w-full px-5 pt-[6rem] pb-10">
    <h1>Accès Admin</h1>

    <div v-if="!isAuthenticated">
      <input
        v-model="password"
        type="password"
        placeholder="Mot de passe"
        class="input"
        @keyup.enter="handleLogin"
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

      <!-- Upload -->
      <h2>Ajouter une image</h2>
      <select v-model="type" class="input">
        <option value="carousel">Carousel</option>
        <option value="project">Projet</option>
      </select>
      <input
        type="file"
        accept="image/*"
        @change="handleFileChange"
        class="input"
      />
      <input
        v-if="type === 'project'"
        v-model="title"
        type="text"
        placeholder="Titre"
        class="input"
      />
      <button @click="handleSubmit" class="btn">Uploader</button>
      <p v-if="uploadStatus" style="margin-top: 12px">{{ uploadStatus }}</p>

      <!-- Carousel -->
      <div class="p-12">
        <h2 class="text-4xl md:text-5xl mb-8 font-bold">Carousel du Home</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="img in images" :key="img.id" class="relative group">
            <img
              :src="img.url"
              :alt="img.title"
              class="rounded shadow w-full h-48 object-cover"
            />
            <button
              @click="deleteImage(img.id)"
              class="absolute top-2 right-2 btn-delete"
            >
              x
            </button>
            <div class="text-xs text-center mt-1">{{ img.title }}</div>
          </div>
        </div>
      </div>

      <!-- Projets -->
      <div class="p-12">
        <h2 class="text-4xl md:text-5xl mb-8 font-bold">Projets</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div v-for="proj in projets" :key="proj.id" class="relative group">
            <img
              :src="proj.url"
              :alt="proj.title"
              class="rounded shadow w-full h-48 object-cover"
            />
            <button
              @click="deleteProjet(proj.id)"
              class="absolute top-2 right-2 btn-delete"
            >
              x
            </button>
            <div class="mt-1 text-xs text-center">
              <template v-if="editingId !== proj.id">
                {{ proj.title }}
                <button @click="startEdit(proj)" class="ml-2 text-blue-600">
                  ✎
                </button>
              </template>
              <template v-else>
                <input
                  v-model="editTitle"
                  @keyup.enter="saveEdit(proj.id)"
                  class="border px-1"
                />
                <button @click="saveEdit(proj.id)" class="ml-1 text-green-600">
                  ✔
                </button>
                <button @click="cancelEdit" class="ml-1 text-red-600">✖</button>
              </template>
            </div>
          </div>
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
  updateProject,
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
const editingId = ref(null);
const editTitle = ref("");

// Édition de titre
function startEdit(proj) {
  editingId.value = proj.id;
  editTitle.value = proj.title;
}

async function saveEdit(id) {
  try {
    const res = await updateProject({ id, title: editTitle.value });
    if (res.success) {
      const p = projets.value.find((p) => p.id === id);
      p.title = editTitle.value;
      editingId.value = null;
    } else {
      alert("Erreur : " + res.error);
    }
  } catch {
    alert("Erreur réseau");
  }
}

function cancelEdit() {
  editingId.value = null;
}

// Login / Logout
const handleLogin = async () => {
  loginError.value = "";
  try {
    const res = await loginAdmin(password.value);
    if (!res.success) {
      loginError.value = res.error || "Erreur de connexion";
      return;
    }
    isAuthenticated.value = true;
    password.value = "";
    await fetchAll();
  } catch {
    loginError.value = "Erreur réseau";
  }
};

const logout = () => {
  // détruire la session côté serveur si tu as un logout.php
  isAuthenticated.value = false;
  password.value = "";
};

// File & upload
const handleFileChange = (e) => {
  file.value = e.target.files[0];
};

const handleSubmit = async () => {
  if (!file.value || (type.value === "project" && !title.value)) {
    uploadStatus.value =
      type.value === "project"
        ? "Image et titre obligatoires !"
        : "Image obligatoire !";
    return;
  }

  uploadStatus.value = "";
  try {
    let res;
    if (type.value === "carousel") {
      res = await addCarouselImage({ image: file.value });
    } else {
      res = await addProject({ title: title.value, image: file.value });
    }
    if (res.error) {
      uploadStatus.value = "Erreur : " + res.error;
    } else {
      uploadStatus.value = "Upload réussi !";
      file.value = null;
      title.value = "";
      await fetchAll();
    }
  } catch (e) {
    uploadStatus.value = "Erreur réseau";
  }
};

// Suppression
const deleteImage = async (id) => {
  if (!confirm("Supprimer cette image ?")) return;
  try {
    const res = await deleteCarouselImage({ id });
    if (res.success) images.value = images.value.filter((i) => i.id !== id);
    else alert("Erreur : " + res.error);
  } catch {
    alert("Erreur réseau");
  }
};

const deleteProjet = async (id) => {
  if (!confirm("Supprimer ce projet ?")) return;
  try {
    const res = await deleteProject({ id });
    if (res.success) projets.value = projets.value.filter((p) => p.id !== id);
    else alert("Erreur : " + res.error);
  } catch {
    alert("Erreur réseau");
  }
};

// Chargement des données
const fetchAll = async () => {
  images.value = await fetchCarousel();
  projets.value = await fetchProjects();
};

onMounted(() => {
  // on peut tenter une vérif côté session
  fetchAll().catch(() => {
    // si non auth, reste sur login
  });
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
.btn-delete {
  background: red;
  color: white;
  border: none;
  padding: 4px 8px;
  border-radius: 4px;
  cursor: pointer;
}
</style>
