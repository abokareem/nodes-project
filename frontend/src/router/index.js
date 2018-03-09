import Vue from 'vue'
import Router from 'vue-router'
import Index from '../components/index/Index.vue'
import Login from '../components/login/Login.vue'
import SingUp from '../components/singup/SingUp.vue'
import Faq from '../components/faq/Faq.vue'
import Contact from '../components/contacts/Contact.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    { path: '/', name: 'index', component: Index },
    { path: '/login', component: Login },
    { path: '/singup', component: SingUp },
    { path: '/faq', component: Faq },
    { path: '/contact', component: Contact }
  ]
})
