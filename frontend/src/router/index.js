import Vue from 'vue'
import Router from 'vue-router'
import Index from '../components/index/Index.vue'
import Login from '../components/login/Login.vue'
import Login2fa from '../components/login/Login2fa.vue'
import SingUp from '../components/singup/SingUp.vue'
import Faq from '../components/faq/Faq.vue'
import Contact from '../components/contacts/Contact.vue'
import NavBar from '../components/header/Header.vue'
import FooTer from '../components/footer/Footer.vue'
import ForgotPassword from '../components/login/forgotten/ForgotPassword.vue'
import ResetPassword from '../components/login/forgotten/ResetPassword.vue'

import Masternodes from '../components/user/paper/Dashboard/Views/Masternodes.vue'
import DashboardLayout from '../components/user/paper/Dashboard/Layout/DashboardLayout.vue'
import UserProfile from '../components/user/paper/Dashboard/Views/UserProfile.vue'
import Notifications from '../components/user/paper/Dashboard/Views/Notifications.vue'
import ConfirmEmail from '../components/user/confirmEmail/ConfirmEmail.vue'
import NotFoundPage from '../components/user/paper/GeneralViews/NotFoundPage.vue'
import DashboardContent from '../components/user/paper/Dashboard/Views/DashboardContent.vue'
import Overview from '../components/user/paper/Dashboard/Views/Overview.vue'
import UserBills from '../components/user/paper/Dashboard/Views/UserBills.vue'

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
    {path: '/login/twofa', name: 'login2fa', components: siteComponenst(Login2fa)},
    {path: '/password/forgot', components: siteComponenst(ForgotPassword)},
    {path: '/password/reset/:token', components: siteComponenst(ResetPassword)},
    {path: '/singup', components: siteComponenst(SingUp)},
    {path: '/faq', components: siteComponenst(Faq)},
    {path: '/contact', components: siteComponenst(Contact)},
    {
      path: '/user',
      name: 'user',
      component: DashboardLayout,
      children: [
        {
          path: 'dashboard',
          name: 'dashboard',
          component: DashboardContent
        },
        {
          path: 'profile',
          name: 'profile',
          component: UserProfile
        },
        {
          path: 'nodes',
          name: 'nodes',
          component: Masternodes
        },
        {
          path: 'bills',
          name: 'bill',
          component: UserBills
        },
        {
          path: '/admin/users',
          name: 'users',
          component: Notifications
        }
      ]
    },
    {path: '/admin/icons', name: 'icons', component: Overview},
    {path: '/email/confirm/:token', component: ConfirmEmail},
    {path: '*', component: NotFoundPage}
  ]
})
