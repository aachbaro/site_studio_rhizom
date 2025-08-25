<template>
  <div class="w-full px-5 pt-[6rem] pb-10">
    <h1 class="text-2xl font-bold mb-6">Accès Admin</h1>

    <!-- Auth -->
    <div v-if="!isAuthenticated" class="max-w-md">
      <input
        v-model="password"
        type="password"
        placeholder="Mot de passe"
        class="input"
        @keyup.enter="handleLogin"
      />
      <button @click="handleLogin" class="btn">Se connecter</button>
      <p class="text-red-600 mt-2" v-if="loginError">{{ loginError }}</p>
    </div>

    <div v-else>
      <div class="flex items-center gap-3 mb-6">
        <button
          @click="logout"
          class="btn"
          style="background: #bbb; color: #222"
        >
          Déconnexion
        </button>
        <span v-if="lastSavedAt" class="text-xs text-black/60"
          >Dernier enregistrement : {{ lastSavedAt }}</span
        >
      </div>

      <!-- Upload -->
      <h2 class="text-xl font-bold mb-2">Ajouter une image</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3 max-w-4xl mb-6">
        <select v-model="type" class="input">
          <option value="carousel">Carousel</option>
          <option value="project">Projet</option>
          <option value="hero">Home Hero</option>
        </select>
        <input
          type="file"
          accept="image/*"
          :multiple="type === 'carousel' || type === 'hero'"
          @change="handleFileChange"
          class="input"
        />
        <div class="flex gap-3">
          <input
            v-if="type === 'project'"
            v-model="title"
            type="text"
            placeholder="Titre"
            class="input flex-1"
          />
          <input
            v-if="type === 'hero'"
            v-model="heroAlt"
            type="text"
            placeholder="Texte alternatif (optionnel)"
            class="input flex-1"
          />
        </div>
        <div class="md:col-span-3">
          <button @click="handleSubmit" class="btn">Uploader</button>
          <p v-if="uploadStatus" class="mt-2">{{ uploadStatus }}</p>
        </div>
      </div>

      <!-- Carousel -->
      <div class="p-6 md:p-12">
        <h2 class="text-2xl md:text-3xl mb-6 font-bold">Carousel du Home</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="img in images" :key="img.id" class="relative group">
            <img
              :src="img.url"
              :alt="img.title || ''"
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

      <!-- Home Hero -->
      <div class="p-6 md:p-12">
        <h2 class="text-2xl md:text-3xl mb-6 font-bold">
          Home Hero (images aléatoires)
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div v-for="img in heroImages" :key="img.id" class="relative group">
            <img
              :src="img.url"
              :alt="img.alt || ''"
              class="rounded shadow w-full h-48 object-cover"
            />
            <button
              @click="deleteHero(img.id)"
              class="absolute top-2 right-2 btn-delete"
            >
              x
            </button>
            <div
              class="text-xs text-center mt-1 truncate"
              :title="img.alt || ''"
            >
              {{ img.alt || "—" }}
            </div>
          </div>
        </div>
      </div>

      <!-- Projets : FULL-CARD DRAG -->
      <div class="p-6 md:p-12">
        <div class="flex items-center justify-between gap-4 mb-4">
          <div class="space-y-1">
            <h2 class="text-2xl md:text-3xl font-bold">Projets</h2>
            <p class="text-xs md:text-sm text-black/60">
              En mode “Réordonner”, saisissez n’importe où sur la carte pour
              déplacer (autosave). Flèches ↑/↓ dispo aussi.
            </p>
          </div>

          <!-- Switch Réordonner -->
          <label class="inline-flex items-center gap-2 cursor-pointer">
            <span class="text-sm">Réordonner</span>
            <input type="checkbox" v-model="reorderMode" class="sr-only" />
            <span
              class="w-12 h-6 rounded-full relative transition"
              :class="reorderMode ? 'bg-black' : 'bg-black/20'"
            >
              <span
                class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition"
                :class="reorderMode ? 'translate-x-6' : 'translate-x-0'"
              ></span>
            </span>
          </label>
        </div>

        <div
          class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
          :class="reorderMode ? 'select-none' : ''"
        >
          <div
            v-for="(proj, idx) in projets"
            :key="proj.id"
            class="relative group border rounded-lg bg-white shadow-sm transition"
            :class="[
              reorderMode
                ? 'cursor-grab active:cursor-grabbing'
                : 'cursor-default',
              dragIndex === idx ? 'opacity-60 scale-[0.985]' : 'opacity-100',
              overIndex === idx ? 'ring-2 ring-black' : 'ring-0',
            ]"
            :draggable="reorderMode"
            :aria-grabbed="dragIndex === idx ? 'true' : 'false'"
            @dragstart="onDragStart(idx, $event)"
            @dragend="onDragEnd"
            @dragover.prevent="reorderMode && onDragOver(idx)"
            @dragenter.prevent="reorderMode && onDragEnter(idx)"
            @dragleave.prevent="reorderMode && onDragLeave(idx)"
            @drop.prevent="reorderMode && onDrop(idx)"
          >
            <!-- Badge ordre -->
            <span
              class="absolute top-2 left-2 text-xs px-2 py-1 rounded-full bg-black text-white"
              >#{{ idx + 1 }}</span
            >

            <!-- Image (désactive le drag natif de l'img pour éviter les conflits) -->
            <img
              :src="proj.url"
              :alt="proj.title"
              class="rounded-t w-full h-48 object-cover"
              draggable="false"
            />

            <!-- Footer carte -->
            <div class="p-3">
              <div class="flex items-center justify-between gap-2">
                <div class="text-xs font-medium truncate" :title="proj.title">
                  <template v-if="editingId !== proj.id">{{
                    proj.title
                  }}</template>
                  <template v-else>
                    <input
                      v-model="editTitle"
                      @keyup.enter="saveEdit(proj.id)"
                      class="border px-1 text-xs"
                    />
                  </template>
                </div>

                <div class="flex items-center gap-1">
                  <!-- Flèches aussi en mode reorder (mais on stoppe le drag sur clic) -->
                  <template v-if="reorderMode">
                    <button
                      @mousedown.stop
                      @touchstart.stop
                      @click="move(idx, -1)"
                      class="px-2 py-1 text-xs border rounded hover:bg-black hover:text-white"
                      :disabled="idx === 0"
                      draggable="false"
                    >
                      ↑
                    </button>
                    <button
                      @mousedown.stop
                      @touchstart.stop
                      @click="move(idx, +1)"
                      class="px-2 py-1 text-xs border rounded hover:bg-black hover:text-white"
                      :disabled="idx === projets.length - 1"
                      draggable="false"
                    >
                      ↓
                    </button>
                  </template>

                  <!-- Edition / suppression hors mode reorder (stop pour éviter drag quand même) -->
                  <template v-else>
                    <button
                      @mousedown.stop
                      @touchstart.stop
                      v-if="editingId !== proj.id"
                      @click="startEdit(proj)"
                      class="px-2 py-1 text-xs border rounded"
                    >
                      ✎
                    </button>
                    <button
                      @mousedown.stop
                      @touchstart.stop
                      v-else
                      @click="saveEdit(proj.id)"
                      class="px-2 py-1 text-xs border rounded text-green-700"
                    >
                      ✔
                    </button>
                    <button
                      @mousedown.stop
                      @touchstart.stop
                      v-if="editingId === proj.id"
                      @click="cancelEdit"
                      class="px-2 py-1 text-xs border rounded text-red-700"
                    >
                      ✖
                    </button>
                    <button
                      @mousedown.stop
                      @touchstart.stop
                      @click="deleteProjet(proj.id)"
                      class="px-2 py-1 text-xs border rounded hover:bg-red-600 hover:text-white"
                    >
                      suppr
                    </button>
                  </template>
                </div>
              </div>

              <p v-if="reorderMode" class="text-[11px] text-black/50 mt-2">
                Glissez la carte pour changer l’ordre
              </p>
            </div>

            <!-- Overlay feedback -->
            <div
              v-if="reorderMode"
              class="absolute inset-0 rounded-lg pointer-events-none"
              :class="
                overIndex === idx ? 'ring-2 ring-black' : 'ring-1 ring-black/10'
              "
            ></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast saved -->
    <div
      v-if="savedToast"
      class="fixed bottom-4 right-4 bg-black text-white text-sm px-4 py-2 rounded-full shadow-lg"
    >
      Ordre enregistré
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
  addCarouselImages,
  deleteProject,
  deleteCarouselImage,
  updateProject,
  getAllHeroImages,
  uploadHeroImages,
  deleteHeroImage,
  reorderProjects,
} from "../../services/api.js";

const password = ref("");
const isAuthenticated = ref(false);
const loginError = ref("");
const files = ref(null);
const title = ref("");
const type = ref("carousel");
const uploadStatus = ref("");

const images = ref([]);
const heroImages = ref([]);
const heroAlt = ref("");
const projets = ref([]);
const editingId = ref(null);
const editTitle = ref("");

const reorderMode = ref(false);
const dragIndex = ref(null);
const overIndex = ref(null);
let saveTimer = null;

const savedToast = ref(false);
const lastSavedAt = ref("");

// Edit titre
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

// Auth
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
  isAuthenticated.value = false;
  password.value = "";
};

// Upload
const handleFileChange = (e) => {
  files.value = e.target.files || null;
};
const handleSubmit = async () => {
  // validations
  if (!files.value || files.value.length === 0) {
    uploadStatus.value = "Image(s) obligatoire(s) !";
    return;
  }
  if (type.value === "project" && !title.value) {
    uploadStatus.value = "Image et titre obligatoires !";
    return;
  }

  uploadStatus.value = "";
  try {
    let res;
    if (type.value === "carousel") {
      // multiple
      res = await addCarouselImages({ files: files.value });
    } else if (type.value === "project") {
      // single (garde l’existant)
      const formLikeSingle = { title: title.value, image: files.value[0] };
      res = await addProject(formLikeSingle);
    } else if (type.value === "hero") {
      // multiple avec alt commun
      res = await uploadHeroImages({
        files: files.value,
        alt: heroAlt.value || "",
      });
    }

    if (res?.error) {
      uploadStatus.value = "Erreur : " + res.error;
    } else {
      uploadStatus.value = `Upload réussi (${res.count ?? 1} fichier(s)) !`;
      // reset
      files.value = null;
      title.value = "";
      heroAlt.value = "";
      await fetchAll();
    }
  } catch {
    uploadStatus.value = "Erreur réseau";
  }
};

// Delete
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
const deleteHero = async (id) => {
  if (!confirm("Supprimer cette image hero ?")) return;
  try {
    const res = await deleteHeroImage({ id });
    if (res.success)
      heroImages.value = heroImages.value.filter((i) => i.id !== id);
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

// Fetch
const fetchAll = async () => {
  const [carous, heroes, projs] = await Promise.all([
    fetchCarousel(),
    getAllHeroImages(),
    fetchProjects(),
  ]);
  images.value = carous;
  heroImages.value = heroes;
  projets.value = projs;
};
onMounted(() => {
  fetchAll().catch(() => {});
});

// DnD full-card
function onDragStart(idx, e) {
  if (!reorderMode.value) return;
  dragIndex.value = idx;
  overIndex.value = null;
  if (e?.dataTransfer) {
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text/plain", String(idx));
  }
}
function onDragEnter(idx) {
  if (dragIndex.value !== null) overIndex.value = idx;
}
function onDragLeave(idx) {
  if (overIndex.value === idx) overIndex.value = null;
}
function onDragOver(idx) {
  overIndex.value = idx;
}
function onDrop(idx) {
  if (dragIndex.value === null || dragIndex.value === idx) return;
  const arr = projets.value.slice();
  const [moved] = arr.splice(dragIndex.value, 1);
  arr.splice(idx, 0, moved);
  projets.value = arr;
  dragIndex.value = null;
  overIndex.value = null;
  requestSave();
}
function onDragEnd() {
  dragIndex.value = null;
  overIndex.value = null;
}

// Boutons ↑/↓ (stop drag)
function move(idx, delta) {
  const newIdx = idx + delta;
  if (newIdx < 0 || newIdx >= projets.value.length) return;
  const arr = projets.value.slice();
  const [m] = arr.splice(idx, 1);
  arr.splice(newIdx, 0, m);
  projets.value = arr;
  requestSave();
}

// Save ordre
function requestSave() {
  if (saveTimer) clearTimeout(saveTimer);
  saveTimer = setTimeout(saveOrder, 200);
}
async function saveOrder() {
  try {
    const ids = projets.value.map((p) => p.id);
    const res = await reorderProjects({ ids });
    if (!res?.success) {
      alert("Erreur lors de l'enregistrement de l'ordre");
      return;
    }
    lastSavedAt.value = new Date().toLocaleTimeString();
    savedToast.value = true;
    setTimeout(() => (savedToast.value = false), 1200);
  } catch {
    alert("Erreur réseau lors de l'enregistrement de l'ordre");
  }
}
</script>

<style scoped>
.input {
  display: block;
  width: 100%;
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  background: #fff;
}
.btn {
  background: #000;
  color: #fff;
  padding: 10px 16px;
  border-radius: 9999px;
  border: none;
  cursor: pointer;
}
.btn-delete {
  background: rgba(0, 0, 0, 0.75);
  color: #fff;
  border: none;
  padding: 4px 8px;
  border-radius: 9999px;
  cursor: pointer;
}
</style>
