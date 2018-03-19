<template>
    <div class="login">
        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
        <div class="login-container">
            <h3 class="login-heading-lead center">{{ $t("login.title") }}</h3>
            <form class="login-form" @submit.prevent="login({ username, password })">
                <div class="login-data-container">
                    <input v-model="username" type="email" placeholder="Email *"
                           class="login-email-input login-input" :class="{'error-login-custom':!isValidEmail}">
                    <span v-if="!isValidEmail">{{$t("validate.email")}}</span>
                </div>
                <div class="login-data-container">
                    <input v-model="password" type="password" placeholder="Password *"
                           class="login-password-input login-input" :class="{'error-login-custom':!isValidPassword}">
                    <span v-if="!isValidPassword">{{$t("validate.password")}}</span>
                </div>
                <div class="login-captcha-container">
                    <!--<captcha></captcha>-->
                </div>
                <div class="login-redirect-container">
                    <p class="login-redirect-register">
                        <router-link to="/password/forgot">Forgot password?</router-link>
                    </p>
                </div>
                <div class="login-button-container">
                    <button type="submit" class="login-button">{{ $t("login.button") }}</button>
                </div>
                <div class="login-redirect-container">
                    <p class="login-redirect-register">{{ $t("login.question") }}
                        <router-link to="/singup">{{ $t("login.redirect") }}</router-link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import captcha from '../captcha/Captcha.vue'
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import validator from '../../services/validator'
import response from '../../services/response'
import PaperNotification from '../user/paper/UIComponents/NotificationPlugin/Notification.vue'

export default{
  components: {
    captcha,
    Spinner,
    PaperNotification
  },
  name: 'Login',
  methods: {
    login (creds) {
      this.isValidEmail = validator.email(this.username)
      this.isValidPassword = validator.password(this.password)
      if (this.isValidEmail && this.isValidPassword) {
        this.snipper = true
        this.$store.dispatch('auth/login', creds).then(res => {
          let data = response.getResponse(res)
          if (data.two_fa) {
            this.$router.push({name: 'login2fa', params: {token: data.token}})
          } else {
            this.$router.push({name: 'dashboard'})
          }
          this.snipper = false
        }).catch(err => {
          this.snipper = false
          response.handleErrors(err, this)
        })
      }
    }
  },
  watch: {
    username () {
      this.isValidEmail = validator.email(this.username)
    },
    password () {
      this.isValidPassword = validator.password(this.password)
    }
  },
  data () {
    return {
      username: '',
      password: '',
      snipper: false,
      isValidEmail: true,
      isValidPassword: true
    }
  }
}
</script>
<style lang="scss">
@import "style";
</style>
