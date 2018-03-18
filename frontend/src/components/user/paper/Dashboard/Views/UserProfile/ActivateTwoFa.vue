<template>
    <div class="activate-twofa">
        <div class="card">
            <div class="header">
                <h4 class="title">Two Factor Authentication</h4>
            </div>
            <modal2fa-activate v-if="!activate && showButton" @buttonChange="activate = true"></modal2fa-activate>
            <modal2fa-deactivate v-if="activate && showButton" @buttonChange="activate = false"></modal2fa-deactivate>
        </div>
    </div>
</template>
<script>
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import modal2faActivate from '../Modals/Modal2fa.vue'
import modal2faDeactivate from '../Modals/Modal2faDeactivate.vue'
import response from '../../../../../../services/response'

export default{
  components: {
    Spinner,
    modal2faActivate,
    modal2faDeactivate
  },
  name: 'ActivateTwoFa',
  beforeCreate () {
    this.$store.dispatch('user/get').then(res => {
      this.activate = this.$store.getters['user/get'].two_fa
      this.showButton = true
    }).catch(err => {
      response.handleErrors(err, this)
      this.showButton = true
    })
  },
  data () {
    return {
      activate: this.$store.getters['user/get'].two_fa,
      snipper: false,
      showButton: false
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
.two-fa-button {
    margin-bottom: 25px;
    margin-top: 50px;
}
.activate-twofa-input {
    margin-top: 20px;
    display: inline-block;
    width: 80%;
}
</style>
