// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import i18n from './i18n'

import vClickOutside from 'v-click-outside'

import Notifications from './components/user/paper/UIComponents/NotificationPlugin'
import SideBar from './components/user/paper/UIComponents/SidebarPlugin'

import Chartist from 'chartist'
import fgInput from './components/user/paper/UIComponents/Inputs/formGroupInput.vue'
import DropDown from './components/user/paper/UIComponents/Dropdown.vue'
import '../static/css/themify-icons.css'

import 'es6-promise/auto'

const GlobalComponents = {
  install (Vue) {
    Vue.component('fg-input', fgInput)
    Vue.component('drop-down', DropDown)
  }
}

Vue.use(GlobalComponents)
Vue.use(vClickOutside)
Vue.use(Notifications)
Vue.use(SideBar)

// global library setup
Object.defineProperty(Vue.prototype, '$Chartist', {
  get () {
    return this.$root.Chartist
  }
})

/* eslint-disable no-new */
new Vue({
  el: '#app',
  render: h => h(App),
  router,
  store,
  i18n,
  data: {
    Chartist: Chartist
  }
})
