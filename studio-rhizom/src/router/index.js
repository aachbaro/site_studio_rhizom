import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Collectif from '../pages/Collectif.vue'
import Projects from '../pages/Projects.vue'
import Catalog from '../pages/Catalog.vue'
import Contact from '../pages/Contact.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/collectif', component: Collectif },
  { path: '/projets', component: Projects },
  { path: '/catalogue', component: Catalog },
  { path: '/contact', component: Contact }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
