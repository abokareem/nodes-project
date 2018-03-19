<template>
    <div class="login-container">
        <!--<slot name="open-modal">
            <button class="two-fa-button btn btn-info btn-fill btn-wd" @click.prevent="openModal">
                Update profile
            </button>
        </slot>-->
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
                                @click.prevent="checkCodeTwoFa({code})">
                            Update
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

  name: 'modal2faCode',

  methods: {
    openModal () {
      this.showModal = true
      this.isValidEmail = true
      this.isValidPassword = true
    },
    checkCodeTwoFa (creds) {
      this.isValidCode = validator.twoFaCode(this.code)
      if (this.isValidCode) {
        this.snipper = true
        request.checkCodeTwoFa(creds).then(res => {
          this.snipper = false
          this.showModal = false
          this.$emit('checkCode', {code: this.code})
        }).catch(err => {
          response.handleErrors(err, this)
          this.snipper = false
        })
      }
    }

  },
  data () {
    return {
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
