import Vue from 'vue'
import Router from 'vue-router'
import Index from '../components/index/Index.vue'
import Login from '../components/login/Login.vue'
import SingUp from '../components/singup/SingUp.vue'
import Faq from '../components/faq/Faq.vue'
import Contact from '../components/contacts/Contact.vue'
import NavBar from '../components/header/Header.vue'
import FooTer from '../components/footer/Footer.vue'

import Icons from '../components/user/paper/Dashboard/Views/Icons.vue'
import DashboardLayout from '../components/user/paper/Dashboard/Layout/DashboardLayout.vue'
import UserProfile from '../components/user/paper/Dashboard/Views/UserProfile.vue'
import Notifications from '../components/user/paper/Dashboard/Views/Notifications.vue'

Vue.use(Router)

const siteComponenst = (def) => {
  return {
    default: def,
    nav: NavBar,
    footer: FooTer
  }
}

export default new Router({
  mode: 'history',
  routes: [
    {path: '/', name: 'index', components: siteComponenst(Index)},
    {path: '/login', name: 'login', components: siteComponenst(Login)},
    {path: '/singup', components: siteComponenst(SingUp)},
    {path: '/faq', components: siteComponenst(Faq)},
    {path: '/contact', components: siteComponenst(Contact)},
    {
      path: '/user',
      name: 'user',
      component: DashboardLayout,
      children: [
        {
          path: 'profile',
          name: 'profile',
          component: UserProfile
        },
        {
          path: 'notify',
          name: 'notify',
          component: Notifications
        }
      ]
    },
    {path: '/admin/icons', name: 'icons', component: Icons}
  ]
})
