// services/api.js

const API_URL = import.meta.env.VITE_API_URL || ""; // Ou fixe à "" si tout est servi du même domaine

// --- PROJECTS ---

export async function fetchProjects() {
  const res = await fetch(`${API_URL}/api/projects.php`);
  return await res.json();
}

export async function addProject({ title, image, token }) {
  const formData = new FormData();
  formData.append("title", title);
  formData.append("image", image);

  const res = await fetch(`${API_URL}/api/projects.php`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
    },
    body: formData,
  });
  return await res.json();
}

export async function deleteProject({ id, token }) {
  const res = await fetch(`${API_URL}/api/projects.php?id=${id}`, {
    method: "DELETE",
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  return await res.json();
}

// --- CAROUSEL ---

export async function fetchCarousel() {
  const res = await fetch(`${API_URL}/api/carousel.php`);
  return await res.json();
}

export async function addCarouselImage({ title, image, token }) {
  const formData = new FormData();
  formData.append("title", title);
  formData.append("image", image);

  const res = await fetch(`${API_URL}/api/carousel.php`, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
    },
    body: formData,
  });
  return await res.json();
}

export async function deleteCarouselImage({ id, token }) {
  const res = await fetch(`${API_URL}/api/carousel.php?id=${id}`, {
    method: "DELETE",
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  return await res.json();
}

// --- AUTH ---

export async function loginAdmin(password) {
  const res = await fetch(`${API_URL}/api/login.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ password }),
  });
  return await res.json();
}

export async function logoutAdmin() {
  // Côté backend, logout.php ne fait rien (avec JWT), tu peux juste effacer le token côté client
  return { success: true };
}

// --- CONTACT ---

export async function sendContact({ name, email, objet, message }) {
  const res = await fetch(`${API_URL}/api/contact.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ name, email, objet, message }),
  });
  return await res.json();
}
