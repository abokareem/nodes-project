<template>
    <div class="login">
        <div class="login-container">
            <h3 class="login-heading-lead center">{{ $t("login.title") }}</h3>
            <form class="login-form" action="" method="post">
                <div class="login-data-container">
                    <input type="email" placeholder="Email *" class="login-email-input login-input">
                </div>
                <div class="login-data-container">
                    <input type="password" placeholder="Password *" class="login-password-input login-input">
                </div>
                <div class="login-captcha-container">
                    <captcha></captcha>
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

export default{
  components: {
    captcha
  },
  name: 'Login'
}
</script>
<style lang="scss">
@import "style";
</style>
