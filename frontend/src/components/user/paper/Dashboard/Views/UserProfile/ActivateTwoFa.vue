<template>
    <div class="activate-twofa">
        <div class="card">
            <div class="header">
                <h4 class="title">Two Factor Authentication</h4>
            </div>
            <div v-if="activate" class="row">
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
                <div class="text-center">
                    <button class="two-fa-button btn btn-success btn-fill btn-wd"
                            @click="activateTwoFa({hash, code})">
                        Press to activate
                    </button>
                </div>
            </div>
            <div class="text-center">
                <button v-if="!activate" class="two-fa-button btn btn-info btn-fill btn-wd"
                        @click="getTwoFaData">
                    Activate Two Factor Authentication
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import request from '../../../../../../services/axios'
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import validator from '../../../../../../services/validator'

export default{
  components: {
    Spinner
  },
  name: 'ActivateTwoFa',
  methods: {
    getTwoFaData () {
      this.activate = true
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
          console.log(res)
          this.snipper = false
        }).catch(err => {
          console.log(err.response)
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
      activate: false,
      snipper: false
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
