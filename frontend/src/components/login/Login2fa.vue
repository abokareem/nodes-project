<template>
    <div class="twofa">
        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
        <h3 class="login2fa-heading-lead center">{{ $t("login2fa.title") }}</h3>
        <form @submit.prevent="login2fa({ code })" class="twofa-form">
            <div class="twofa-data-container">
                <input v-model="code" type="text" placeholder="Code *"
                       class="twofa-code-input" :class="{'error-login-custom':!isValidCode}">
                <span v-if="!isValidCode">{{$t("validate.code2fa")}}</span>
            </div>
            <div class="login-button-container login2fa-button-container">
                <button type="submit" class="login-button login2fa-button">{{ $t("login.button") }}</button>
            </div>
        </form>
    </div>
</template>
<script>
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import validator from '../../services/validator'
import response from '../../services/response'
export default{
  components: {
    Spinner
  },
  name: 'Login2fa',
  beforeCreate () {
    if (!this.$route.params.token) {
      this.$router.push({name: 'login'})
    }
  },
  methods: {
    login2fa (creds) {
      creds.token = this.$route.params.token
      this.isValidCode = validator.twoFaCode(this.code)
      if (this.isValidCode) {
        this.snipper = true
        this.$store.dispatch('auth/login2fa', creds).then(res => {
          this.snipper = false
          this.$router.push({name: 'dashboard'})
        }).catch(err => {
          response.handleErrors(err, this)
          this.snipper = false
        })
      }
    }
  },
  watch: {
    code () {
      this.isValidCode = validator.twoFaCode(this.code)
    }
  },
  data () {
    return {
      isValidCode: true,
      snipper: false,
      code: ''
    }
  }
}
</script>
<style lang="scss">
@import "../../assets/partials/_const.scss";
@import "../../../static/css/themify-icons.css";
.twofa {
    display: inline-block;
    width: 100%;
    padding-top: 205px;
    min-height: calc(100vh - 259px);
}
.login2fa-heading-lead {
    font-size: 2.5em;
    font-weight: 500;
    font-family: "Quicksand", sans-serif;
    color: #3c3c3c;
}
.twofa-data-container {
    width: 100%;
    text-align: left;
    span {
        color: #FF4014;
        display: inline-block;
        width: auto;
        margin-bottom: 10px;
        margin-left: 32%;
    }
}
.twofa-code-input {
    display: inline-block;
    margin-left: 32%;
    height: 45px;
    margin-bottom: 14px;
    background: #f8f8f8;
    padding: 6px 12px;
    font-size: 14px;
    color: #555;
    border: 1px solid #e1e1e1;
    border-radius: 4px;
    width: 600px;
}
@media only screen and (min-width: $minimal) and (max-width: $medium) {
    .twofa-data-container, .login2fa-button-container {
        width: 90%;
        span {
            margin-left: 0;
        }
    }
    .twofa-code-input {
        width: 100%;
        margin-left: 0;
    }
    .login2fa-button-container > .login2fa-button {
        margin-left: 0;
    }
}
</style>
