<template>
        <div class="wrapper">
            <side-bar type="sidebar" :sidebar-links="$sidebar.sidebarLinks">

            </side-bar>
            <notifications>

            </notifications>
            <div class="main-panel">
                <top-navbar></top-navbar>

                <dashboard-content @click.native="toggleSidebar">

                </dashboard-content>
                <content-footer></content-footer>
            </div>
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
