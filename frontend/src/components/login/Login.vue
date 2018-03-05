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
                    <div class="login-captcha" id="g-recaptcha" :data-sitekey="rcapt_sig_key"></div>
                </div>
                <div class="login-button-container">
                    <button type="submit" class="login-button">{{ $t("login.button") }}</button>
                </div>
                <div class="login-redirect-container">
                    <p class="login-redirect-register">{{ $t("login.question") }}
                        <a href="">{{ $t("login.redirect") }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default{
  name: 'Login',
  mounted () {
    if (window.grecaptcha) {
      this.rcapt_id = window.grecaptcha.render(
        document.getElementById('g-recaptcha'), {sitekey: this.rcapt_sig_key})
    }
  },
  data () {
    return {
      rcapt_sig_key: '6LeosEoUAAAAAFTdd7Y1drhe58oDtn14rp_OjB0h',
      rcapt_id: 0
    }
  }
}
</script>
<style lang="scss">
@import "style";
</style>
