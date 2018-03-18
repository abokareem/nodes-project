<template>
    <div class="login-container">
        <button class="two-fa-button btn btn-info btn-fill btn-wd" @click="getTwoFaData">Activate Two Factor Authentication</button>

        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">Activate two factor authentication</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <img :src="qrCode">
                        <h4 v-if="hash" class="title">Key for mobile: {{hash}}</h4><br>
                        <h4 v-if="reserveCode" class="title">
                            Reserve code for reset two factor authentication: {{reserveCode}}
                        </h4>
                        <div class="text-center">
                            <label for="activate-input-code">
                                Mobile code
                            </label>
                            <input class="activate-twofa-input form-control border-input"
                                   type="text"
                                   id="activate-input-code"
                                   placeholder="Mobile code *"
                                   v-model="code">
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-success btn-fill btn-wd"
                                @click="activateTwoFa({hash, code})">
                            Press to activate
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
    getTwoFaData () {
      this.showModal = true
      this.isValidEmail = true
      this.isValidPassword = true
      this.snipper = true
      request.getTwoFa().then(res => {
        this.hash = res.data.data.hash
        this.qrCode = res.data.data.qr_code
        this.reserveCode = res.data.data.reserve_code
        this.snipper = false
      }).catch(err => {
        this.activate = false
        console.log(err.response)
      })
    },
    activateTwoFa (creds) {
      this.isValidCode = validator.twoFaCode(this.code)
      if (this.isValidCode) {
        this.snipper = true
        creds.reserve_code = this.reserveCode
        request.activateTwoFa(creds).then(res => {
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
