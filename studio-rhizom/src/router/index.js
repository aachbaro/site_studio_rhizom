import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home/index.vue'
import Projets from '../pages/Projets/index.vue'
import Studio from '../pages/Studio/index.vue'
import Contact from '../pages/Contact/index.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/projets', component: Projets },
  { path: '/studio', component: Studio },
  { path: '/contact', component: Contact }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
