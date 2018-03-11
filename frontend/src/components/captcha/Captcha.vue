<template>
    <div class="captcha" id="g-recaptcha" :data-sitekey="rcapt_sig_key"></div>
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
<style lang="scss">
.captcha {
    text-align: left;
    div {
        margin: 0 !important;
    }
}
</style>
