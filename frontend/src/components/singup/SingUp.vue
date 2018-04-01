<template>
    <div class="sing-up">
        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
        <div class="sing-up-container">
            <h3 class="sing-up-heading-lead center">{{ $t("singUp.title") }}</h3>
            <form class="sing-up-form" @submit.prevent="register({ email, password, name, subscribe }, $event)">
                <div class="sing-up-data-container">
                    <input type="text" placeholder="Name *" v-model="name"
                           class="sing-up-name-input sing-up-input"
                           :class="{'error-login-custom':!isValidName}">
                    <span class="error-sing-up-validate" v-if="!isValidName">{{$t("validate.name")}}</span>
                </div>
                <div class="sing-up-data-container">
                    <input type="email" placeholder="Email *" v-model="email"
                           class="sing-up-email-input sing-up-input"
                           :class="{'error-login-custom':!isValidEmail}">
                    <span class="error-sing-up-validate" v-if="!isValidEmail">{{$t("validate.email")}}</span>
                </div>
                <div class="sing-up-data-container">
                    <input type="password" placeholder="Password *" v-model="password"
                           class="sing-up-password-input sing-up-input"
                           :class="{'error-login-custom':!isValidPassword}">
                    <span class="error-sing-up-validate" v-if="!isValidPassword">{{$t("validate.password")}}</span>
                </div>
                <div class="sing-up-data-container">
                    <input type="password" v-model="passwordConfirm" placeholder="Confirm Password *"
                           class="sing-up-password-input sing-up-input"
                           :class="{'error-login-custom':!isPasswordConfirmed}">
                    <span class="error-sing-up-validate" v-if="!isPasswordConfirmed">{{$t("validate.confirmedPassword")}}</span>
                </div>
                <div class="sing-up-data-container sing-up-captcha-container">
                    <captcha></captcha>
                    <span class="error-sing-up-validate" v-if="!isValidCaptcha">{{$t("validate.captcha")}}</span>
                </div>
                <div class="sing-up-data-container">
                    <div class="sing-up-term-container">
                        <input type="checkbox" v-model="subscribe" name="sign-up-term">
                        <span>{{ $t("singUp.terms.text") }}
                            <router-link :to="{ name: 'terms'}">{{ $t("singUp.terms.url") }}</router-link>.
                    </span>
                    </div>
                </div>
                <div class="sing-up-button-container">
                    <button type="submit" class="sing-up-button">{{ $t("singUp.button") }}</button>
                </div>
                <div class="sing-up-redirect-container">
                    <p class="sing-up-redirect-register">{{ $t("singUp.question") }}
                        <router-link to="/login">{{ $t("singUp.redirect") }}</router-link>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import request from '../../services/axios'
import captcha from '../captcha/Captcha.vue'
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import validator from '../../services/validator'

export default {
  components: {
    captcha,
    Spinner
  },
  name: 'sing-up',
  methods: {
    register (creds, event) {
      creds.captchaValue = event.target[4].value
      this.isValidName = validator.name(this.name)
      this.isValidPassword = validator.password(this.password)
      this.isValidEmail = validator.email(this.email)
      this.isValidCaptcha = validator.captcha(creds.captchaValue)
      this.isPasswordConfirmed = validator.passwordConfirm(this.password, this.passwordConfirm)
      if (
        this.isValidEmail &&
        this.isValidPassword &&
        this.isValidName &&
        this.isPasswordConfirmed &&
        this.isValidCaptcha
      ) {
        this.snipper = true
        const lang = navigator.language || navigator.userLanguage
        creds.language = lang.substring(0, 2)
        request.register(creds, this.$i18n.locale).then(res => {
          this.snipper = false
          this.$notifications.notify({
            message: '<h3>' + res.data.message + '</h3>',
            icon: 'ti-bell',
            horizontalAlign: 'right',
            verticalAlign: 'bottom',
            type: 'info',
            timeout: 2000
          })
        }).catch(err => {
          this.snipper = false
          this.$notifications.notify({
            message: '<h3>' + err.response.data.errors.email[0] + '</h3>',
            icon: 'ti-bell',
            horizontalAlign: 'right',
            verticalAlign: 'bottom',
            type: 'info',
            timeout: 2000
          })
        })
      }
    }
  },
  watch: {
    email () {
      this.isValidEmail = validator.email(this.email)
    },
    password () {
      this.isValidPassword = validator.password(this.password)
      this.isPasswordConfirmed = validator.passwordConfirm(this.password, this.passwordConfirm)
    },
    name () {
      this.isValidName = validator.name(this.name)
    },
    passwordConfirm () {
      this.isPasswordConfirmed = validator.passwordConfirm(this.password, this.passwordConfirm)
    }
  },
  data () {
    return {
      name: '',
      email: '',
      password: '',
      passwordConfirm: '',
      subscribe: '',
      snipper: false,
      isValidName: true,
      isValidPassword: true,
      isValidEmail: true,
      isPasswordConfirmed: true,
      isValidCaptcha: true
    }
  }
}
</script>
<style lang="scss">
@import "style";
</style>
