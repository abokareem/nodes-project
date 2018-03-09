<template>
    <div class="login-captcha" id="g-recaptcha" :data-sitekey="rcapt_sig_key"></div>
</template>
<script>
export default{
  name: 'Captcha',
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
