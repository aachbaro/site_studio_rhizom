import { createRouter, createWebHistory } from "vue-router";
import Home from "../pages/Home/index.vue";
import Projets from "../pages/Projets/index.vue";
import Studio from "../pages/Studio/index.vue";
import Contact from "../pages/Contact/index.vue";
import Admin from "../pages/Admin/index.vue";
import MentionsLegales from "../pages/mentions-legales/MentionsLegales.vue";
import Cookies from "../pages/cookies/Cookies.vue";

const routes = [
  { path: "/", component: Home },
  { path: "/projets", component: Projets },
  { path: "/studio", component: Studio },
  { path: "/contact", component: Contact },
  { path: "/admin", component: Admin },
  {
    path: "/mentions-legales",
    name: "MentionsLegales",
    component: MentionsLegales,
  },
  { path: "/cookies", name: "Cookies", component: Cookies },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 };
  },
});

export default router;
