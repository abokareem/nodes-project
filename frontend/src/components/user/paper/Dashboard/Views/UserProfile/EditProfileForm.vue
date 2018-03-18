<template>
    <div class="card">
        <div class="header">
            <h4 class="title">Edit Profile</h4>
        </div>
        <div v-if="showContent" class="content">
            <form>
                <div class="row">
                    <div class="col-md-4">

                        <fg-input type="text"
                                  label="Username"
                                  placeholder="Username"
                                  v-model="user.name">
                        </fg-input>
                    </div>
                    <div class="col-md-4">
                        <fg-input type="email"
                                  label="Email"
                                  placeholder="Email"
                                  v-model="user.email">
                        </fg-input>
                    </div>
                    <div class="col-md-4">
                        <fg-input type="password"
                                  label="Password"
                                  placeholder="Password"
                                  v-model="user.password">
                        </fg-input>
                    </div>
                </div>
                <div class="text-center">
                    <button v-if="!is2faEnabled" type="submit" class="btn btn-info btn-fill btn-wd"
                            @click.prevent="updateProfile">
                        Update Profile
                    </button>
                    <modal2fa-check v-if="is2faEnabled" @checkCode="updateData"></modal2fa-check>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</template>
<script>
import response from '../../../../../../services/response'
import modal2faCheck from '../Modals/Modal2faCode.vue'
import Spinner from 'vue-spinner-component/src/Spinner.vue'

export default {
  components: {
    Spinner,
    modal2faCheck
  },
  data () {
    return {
      user: {
        name: '',
        email: '',
        password: ''
      },
      is2faEnabled: '',
      showContent: false,
      showModal2fa: false
    }
  },
  beforeCreate () {
    this.$store.dispatch('user/get').then(res => {
      this.user.name = this.$store.getters['user/get'].name
      this.user.email = this.$store.getters['user/get'].email
      this.is2faEnabled = this.$store.getters['user/get'].two_fa
      this.showContent = true
    }).catch(err => {
      response.handleErrors(err, this)
      this.showContent = true
    })
  },
  computed: {
    is2faEnabled () {
      this.is2faEnabled = this.$store.getters['user/get'].two_fa
    }
  },
  methods: {
    updateProfile () {
      if (this.user.is2faEnabled) {
        this.showModal2fa = true
      } else {
        this.updateData()
      }
    },
    updateData (creds) {
      if (this.user.password === '') {
        delete this.user.password
      }
      if (this.user.email === this.$store.getters['user/get'].email) {
        delete this.user.email
      }
      if (creds) {
        this.user.twofa = creds.code
      }
      this.$store.dispatch('user/update', this.user).then(res => {
        this.$notifications.notify(
          {
            message: 'Profile was updated.',
            icon: 'ti-bell',
            horizontalAlign: 'right',
            verticalAlign: 'bottom',
            type: 'info',
            timeout: 2000
          })
      }).catch(err => {
        response.handleErrors(err, this)
      })
    }
  }
}

</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
</style>
