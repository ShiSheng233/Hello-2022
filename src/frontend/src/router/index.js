import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Subject from '../views/Subject.vue'
Vue.use(VueRouter)
console.log('Front-end By LineSoft')
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/subject/:id',
    name: 'subject',
    component: Subject
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
