// frontend/src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Volunteers from '../pages/Volunteers.vue'
import Events from '../pages/Events.vue'
import MatchResults from '../pages/MatchResults.vue'
import Admin from '../pages/Admin.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/volunteers', component: Volunteers },
  { path: '/events', component: Events },
  { path: '/matches', component: MatchResults },
  { path: '/admin', component: Admin }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router