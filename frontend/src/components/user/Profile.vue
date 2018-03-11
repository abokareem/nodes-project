<template>
    <div class="wrapper">
        <side-bar type="navbar" :sidebar-links="$sidebar.sidebarLinks">

        </side-bar>
        <notifications>

        </notifications>
        <div class="main-panel">
            <top-navbar></top-navbar>

            <!--<dashboard-content @click.native="toggleSidebar">

            </dashboard-content>-->
            <user-profile></user-profile>

            <content-footer></content-footer>
        </div>
    </div>
</template>
<script>
import TopNavbar from './paper/Dashboard/Layout/TopNavbar.vue'
import ContentFooter from './paper/Dashboard/Layout/ContentFooter.vue'
import DashboardContent from './paper/Dashboard/Layout/Content.vue'
import UserProfile from './paper/Dashboard/Views/UserProfile.vue'

export default{
  components: {
    TopNavbar,
    ContentFooter,
    DashboardContent,
    UserProfile
  },
  name: 'Profile',
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
/*@import "../../assets/sass/bootstrap.css";
@import "../../assets/sass/paper-dashboard.scss";*/
</style>
