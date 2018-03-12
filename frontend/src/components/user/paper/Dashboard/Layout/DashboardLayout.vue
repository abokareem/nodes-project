<template>
    <div :class="{'nav-open': $sidebar.showSidebar}">
        <div class="wrapper">
            <side-bar type="sidebar" :sidebar-links="$sidebar.sidebarLinks">

            </side-bar>
            <div class="main-panel">
                <top-navbar></top-navbar>

                <dashboard-content @click.native="toggleSidebar">

                </dashboard-content>
                <content-footer></content-footer>
            </div>
        </div>
        <side-bar type="navbar" :sidebar-links="$sidebar.sidebarLinks">
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
export default {
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent
  },
  beforeCreate () {
    if (!this.$store.state.auth.isLogged) {
      this.$router.push('/login')
    }
    this.$store.dispatch('user/get').catch(err => {
      this.$router.push('/login')
    })
  },
  methods: {
    toggleSidebar () {
      if (this.$sidebar.showSidebar) {
        this.$sidebar.displaySidebar(false)
      }
    }
  }
}

</script>
<style lang="scss" scoped>
@import "../../../../../assets/sass/bootstrap.css";
@import "../../../../../assets/sass/paper-dashboard.scss";
</style>
