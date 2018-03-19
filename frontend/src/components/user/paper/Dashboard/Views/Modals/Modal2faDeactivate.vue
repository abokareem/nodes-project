<template>
    <div class="login-container">
        <button class="two-fa-button btn btn-danger btn-fill btn-wd" @click="openModal">
            Deactivate Two Factor Authentication
        </button>

        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">Deactivate two factor authentication</h3>
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
                        <button class="two-fa-button btn btn-danger btn-fill btn-wd"
                                @click="deactivateTwoFa({code})">
                            Press to deactivate
                        </button>
                    </div>
                </div>
            </window-modal>
        </div>
    </div>
</template>
<script>
import WindowModal from '../../../../../modal/Modal'
import request from '../../../../../../services/axios'
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import validator from '../../../../../../services/validator'
import response from '../../../../../../services/response'

export default {

  components: {
    Spinner,
    WindowModal
  },

  name: 'modal2fa',

  methods: {
    openModal () {
      this.showModal = true
      this.isValidEmail = true
      this.isValidPassword = true
    },
    deactivateTwoFa (creds) {
      this.isValidCode = validator.twoFaCode(this.code)
      if (this.isValidCode) {
        this.snipper = true
        this.$store.dispatch('user/deactivateTwoFa', creds).then(res => {
          response.handleSuccess(res, this)
          this.snipper = false
          this.showModal = false
          this.$emit('buttonChange')
        }).catch(err => {
          response.handleErrors(err, this)
          this.snipper = false
        })
      }
    }

  },
  data () {
    return {
      hash: '',
      qrCode: '',
      reserveCode: '',
      code: '',
      isValidCode: true,
      activate: this.$store.getters['user/get'].two_fa,
      snipper: false,
      showModal: false
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
input {
    margin-bottom: 10px;
}
span {
    color: red;
}
.error {
    border: 1px solid red;
}
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
