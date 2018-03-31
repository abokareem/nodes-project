import Sidebar from './SideBar.vue'

const SidebarStore = {
  showSidebar: false,
  adminLinks: [
    {
      name: 'Users',
      icon: 'ti-user',
      path: '/admin/users'
    },
    {
      name: 'Users Bills',
      icon: 'ti-panel',
      path: '/admin/users/bills'
    },
    {
      name: 'Commissions',
      icon: 'ti-wallet',
      path: '/admin/commissions'
    },
    {
      name: 'Masternodes',
      icon: 'ti-view-list-alt',
      path: '/admin/nodes'
    },
    {
      name: 'Node Withdrawals',
      icon: 'ti-angle-double-left',
      path: '/admin/nodes/withdrawals'
    },
    {
      name: 'Currencies',
      icon: 'ti-star',
      path: '/admin/currencies'
    },
    {
      name: 'Money Withdrawals',
      icon: 'ti-angle-double-left',
      path: '/admin/money/withdrawals'
    }
  ],
  sidebarLinks: [
    {
      name: 'Dashboard',
      icon: 'ti-panel',
      path: '/user/dashboard'
    },
    {
      name: 'User Profile',
      icon: 'ti-user',
      path: '/user/profile'
    },
    {
      name: 'Masternodes',
      icon: 'ti-view-list-alt',
      path: '/user/nodes'
    },
    {
      name: 'Bills',
      icon: 'ti-wallet',
      path: '/user/bills'
    }
  ],
  displaySidebar (value) {
    this.showSidebar = value
  }
}

const SidebarPlugin = {

  install (Vue) {
    Vue.mixin({
      data () {
        return {
          sidebarStore: SidebarStore
        }
      }
    })

    Object.defineProperty(Vue.prototype, '$sidebar', {
      get () {
        return this.$root.sidebarStore
      }
    })
    Vue.component('side-bar', Sidebar)
  }
}

export default SidebarPlugin
