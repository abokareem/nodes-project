<template>
    <div class="login">
        <div class="login-container">
            <h3 class="login-heading-lead center">{{ $t("login.title") }}</h3>
            <form class="login-form" @submit.prevent="login({ username, password })">
                <div class="login-data-container">
                    <input v-model="username" type="email" placeholder="Email *" class="login-email-input login-input">
                </div>
                <div class="login-data-container">
                    <input v-model="password" type="password" placeholder="Password *"
                           class="login-password-input login-input">
                </div>
                <spinner style="position: absolute; top: 50%; left: 50%;" v-if="snipper" :size="60"></spinner>
                <div class="login-captcha-container">
                    <!--<captcha></captcha>-->
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

export default{
  components: {
    captcha,
    Spinner
  },
  name: 'Login',
  methods: {
    login (creds) {
      this.snipper = true
      this.$store.dispatch('auth/login', creds).then(res => {
        this.$router.push('user')
        this.snipper = false
      }).catch(err => {
        this.snipper = false
        console.log(err.response)
      })
    }
  },
  data () {
    return {
      username: '',
      password: '',
      snipper: false
    }
  }
}
</script>
<style lang="scss">
@import "style";
</style>
