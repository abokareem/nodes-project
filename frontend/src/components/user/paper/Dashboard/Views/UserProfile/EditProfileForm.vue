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
                                  v-model="name">
                        </fg-input>
                    </div>
                    <div class="col-md-4">
                        <fg-input type="email"
                                  label="Email"
                                  placeholder="Email"
                                  v-model="email">
                        </fg-input>
                    </div>
                    <div class="col-md-4">
                        <fg-input type="password"
                                  label="Password"
                                  placeholder="Password"
                                  v-model="password">
                        </fg-input>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-info btn-fill btn-wd"
                            @click.prevent="updateProfile({name, email, password, code})">
                        Update Profile
                    </button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">Two Factor Authentication</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <div class="text-center">
                            <label for="activate-input-code">
                                Code
                            </label>
                            <input class="activate-twofa-input form-control border-input"
                                   type="text"
                                   id="activate-input-code"
                                   placeholder="Code *"
                                   v-model="code">
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-info btn-fill btn-wd"
                                @click.prevent="checkCodeTwoFa({name, email, password, code})">
                            Update
                        </button>
                    </div>
                </div>
            </window-modal>
        </div>
    </div>
</template>
<script>
import response from '../../../../../../services/response'
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import WindowModal from '../../../../../modal/Modal'
import request from '../../../../../../services/axios'
import validator from '../../../../../../services/validator'

export default {
  components: {
    Spinner,
    WindowModal
  },
  data () {
    return {
      name: '',
      email: '',
      password: '',
      is2faEnabled: '',
      showContent: false,
      showModal2fa: false,
      showModal: false,
      code: '',
      snipper: false
    }
  },
  beforeCreate () {
    this.$store.dispatch('user/get').then(res => {
      this.name = this.$store.getters['user/get'].name
      this.email = this.$store.getters['user/get'].email
      this.is2faEnabled = this.$store.getters['user/get'].two_fa
      this.showContent = true
    }).catch(err => {
      response.handleErrors(err, this)
      this.showContent = true
    })
  },
  methods: {
    updateProfile (creds) {
      this.is2faEnabled = this.$store.getters['user/get'].two_fa
      if (this.is2faEnabled) {
        this.showModal = true
      } else {
        this.updateData(creds)
      }
    },
    checkCodeTwoFa (creds) {
      this.isValidCode = validator.twoFaCode(this.code)
      if (this.isValidCode) {
        this.snipper = true
        request.checkCodeTwoFa(creds).then(res => {
          this.snipper = false
          this.showModal = false
          this.updateData(creds)
        }).catch(err => {
          response.handleErrors(err, this)
          this.snipper = false
        })
      }
    },
    updateData (creds) {
      if (this.password === '') {
        delete creds.password
      }
      if (this.email === this.$store.getters['user/get'].email) {
        delete creds.email
      }
      if (creds.code) {
        creds.twofa = creds.code
      }
      this.$store.dispatch('user/update', creds).then(res => {
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
