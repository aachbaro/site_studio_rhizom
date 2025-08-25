// services/api.js

const API_BASE = ""; // tout est servi du même domaine

/**
 * Wrapper autour de fetch pour gérer :
 * - l'envoi du cookie de session (credentials: 'include')
 * - la levée d'erreur en cas de 401
 * - le parsing JSON
 */
async function request(
  path,
  { method = "GET", headers = {}, body, json = false } = {}
) {
  if (json) {
    headers["Content-Type"] = "application/json";
  }
  const res = await fetch(`${API_BASE}${path}`, {
    method,
    headers,
    body,
    credentials: "include", // envoie automatiquement PHPSESSID
  });

  if (res.status === 401) {
    throw new Error("Session expirée. Veuillez vous reconnecter.");
  }
  return res.json();
}

//
// — AUTH —
//

/**
 * Login : envoie le mot de passe en JSON, récupère { success, error }.
 * La session PHP est démarrée par login.php côté serveur.
 */
export function loginAdmin(password) {
  return request("/api/login.php", {
    method: "POST",
    json: true,
    body: JSON.stringify({ password }),
  });
}

/**
 * Logout : appelle logout.php pour détruire la session, ou simplement fait un GET
 * (à adapter si tu crées un logout.php qui fait session_destroy()).
 */
export function logoutAdmin() {
  // si tu as un logout.php
  // return request("/api/logout.php", { method: "POST" });
  // sinon, on peut juste renvoyer success :
  return Promise.resolve({ success: true });
}

//
// — PROJECTS —
//

export function fetchProjects() {
  return request("/api/projects.php", { method: "GET" });
}

export function addProject({ title, image }) {
  const form = new FormData();
  form.append("title", title);
  form.append("image", image);
  return request("/api/projects.php", { method: "POST", body: form });
}

export function updateProject({ id, title }) {
  const form = new FormData();
  form.append("_method", "PUT");
  form.append("id", id);
  form.append("title", title);
  return request("/api/projects.php", {
    method: "POST",
    body: form,
  });
}

export function deleteProject({ id }) {
  const form = new FormData();
  form.append("_method", "DELETE");
  form.append("id", id);
  return request("/api/projects.php", {
    method: "POST",
    body: form,
  });
}

export function reorderProjects({ ids }) {
  return request("/api/projects.php", {
    method: "POST",
    json: true,
    body: JSON.stringify({ action: "reorder", ids }),
  });
}

//
// — CAROUSEL —
//

export function fetchCarousel() {
  return request("/api/carousel.php", { method: "GET" });
}

export function addCarouselImages({ files }) {
  const form = new FormData();
  // name "images[]" pour le multiple
  Array.from(files).forEach((f) => form.append("images[]", f));
  return request("/api/carousel.php", { method: "POST", body: form });
}

export function deleteCarouselImage({ id }) {
  return request(`/api/carousel.php?id=${id}`, { method: "DELETE" });
}

//
// — CONTACT —
//

export function sendContact({ name, email, objet, message }) {
  return request("/api/contact.php", {
    method: "POST",
    json: true,
    body: JSON.stringify({ name, email, objet, message }),
  });
}

//
// — HERO IMAGE —
//

export function getAllHeroImages() {
  return request("/api/home_hero.php", { method: "GET" });
}

export function getRandomHeroImage() {
  return request("/api/home_hero.php?mode=random", { method: "GET" });
}

export function uploadHeroImages({ files, alt = "" }) {
  const form = new FormData();
  Array.from(files).forEach((f) => form.append("images[]", f));
  if (alt) form.append("alt", alt);
  return request("/api/home_hero.php", { method: "POST", body: form });
}

export function deleteHeroImage({ id }) {
  return request(`/api/home_hero.php?id=${id}`, { method: "DELETE" });
}
