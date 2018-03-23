<template>
    <div :class="{'nav-open': $sidebar.showSidebar}">
        <div class="wrapper">
            <side-bar v-if="showSidebarLinks" type="sidebar"
                      :sidebar-links="isAdmin ? $sidebar.adminLinks : $sidebar.sidebarLinks">

            </side-bar>
            <div class="main-panel">
                <top-navbar></top-navbar>

                <dashboard-content @click.native="toggleSidebar">

                </dashboard-content>
                <content-footer></content-footer>
            </div>
        </div>
        <side-bar v-if="showSidebarLinks" type="navbar"
                  :sidebar-links="isAdmin ? $sidebar.adminLinks : $sidebar.sidebarLinks">
            <ul class="nav navbar-nav">
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <i class="ti-panel"></i>
                        <p>Stats</p>
                    </a>
                </li>
                <drop-down title="5 Notifications" icon="ti-bell">

                    <li><a>Notification 1</a></li>
                    <li><a>Notification 2</a></li>
                    <li><a>Notification 3</a></li>
                    <li><a>Notification 4</a></li>
                    <li><a>Another notification</a></li>

                </drop-down>
                <li>
                    <a>
                        <i class="ti-settings"></i>
                        <p>Settings</p>
                    </a>
                </li>
                <li class="divider"></li>
            </ul>
        </side-bar>
    </div>
</template>
<script>
import TopNavbar from './TopNavbar.vue'
import ContentFooter from './ContentFooter.vue'
import DashboardContent from './Content.vue'
import response from '../../../../../services/response'

export default {
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent
  },
  beforeCreate () {
    if (!this.$store.getters['auth/isLoggedIn']) {
      this.$router.push('/login')
    }
    this.$store.dispatch('user/get').then(res => {
      let data = response.getResponse(res)
      if (data.group === this.$store.state.groups.admin) {
        this.isAdmin = true
        this.$router.push({name: 'users'})
      } else {
        if (this.$route.name === 'user') {
          this.$router.push({name: 'dashboard'})
        }
      }
      this.showSidebarLinks = true
    }).catch(err => {
      this.showSidebarLinks = true
      response.handleErrors(err, this)
    })
  },
  methods: {
    toggleSidebar () {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false)
      }
    }
  },
  data () {
    return {
      isAdmin: false,
      showSidebarLinks: false
    }
  }
}

</script>
<style lang="scss" scoped>
@import "../../../../../assets/sass/bootstrap.css";
@import "../../../../../assets/sass/paper-dashboard.scss";
</style>
