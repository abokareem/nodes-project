<template>
    <div class="forgot-password">
        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
        <div class="forgot-password-container">
            <h3 class="password-lead center">{{ $t("reset.password.title") }}</h3>
            <form class="forgot-password-form" @submit.prevent="sendEmail({ email })">
                <div class="forgot-password-data-container">
                    <input v-model="email" type="email" placeholder="Email *"
                           class="login-email-input forgot-input" :class="{'error-login-custom':!isValidEmail}">
                    <span v-if="!isValidEmail">{{$t("validate.email")}}</span>
                </div>
                <div class="forgot-password-button-container">
                    <button type="submit" class="forgot-password-button">{{ $t("reset.password.button") }}</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import validator from '../../../services/validator'
import request from '../../../services/axios'
import response from '../../../services/response'

export default{
  components: {
    Spinner
  },
  methods: {
    sendEmail (creds) {
      this.isValidEmail = validator.email(this.email)
      if (this.isValidEmail) {
        this.snipper = true
        request.forgotPassword(creds).then(res => {
          response.handleSuccess(res, this)
          this.snipper = false
        }).catch(err => {
          response.handleErrors(err, this)
          this.snipper = false
        })
      }
    }
  },
  watch: {
    email () {
      this.isValidEmail = validator.email(this.email)
    }
  },
  data () {
    return {
      email: '',
      snipper: false,
      isValidEmail: true
    }
  }
}
</script>
<style lang="scss">
@import "../../../assets/partials/_const.scss";
.forgot-password {
    display: inline-block;
    width: 100%;
    padding-top: 197px;
    padding-bottom: 50px;
    min-height: calc(100vh - 250px);
}
.forgot-password-container {
    margin-right: auto;
    margin-left: auto;
    padding-left: 15px;
    padding-right: 15px;
    font-family: "OpenSans", sans-serif;
    color: #3c3c3c;
    font-size: 16px;
    line-height: 1.75;
    font-weight: 300;
}
.password-lead {
    font-size: 2.5em;
    font-weight: 500;
    font-family: "Quicksand", sans-serif;
}
.forgot-password-data-container, .forgot-password-button-container {
    width: 100%;
    text-align: left;
}
.forgot-password-data-container {
    span {
        color: #FF4014;
        display: inline-block;
        width: auto;
        margin-bottom: 10px;
        margin-left: 32%;
    }
}
.forgot-input {
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
.forgot-password-button {
    display: inline-block;
    margin-left: 32%;
    background-color: #f7921a;
    border: 3px solid #f7921a;
    padding: 20px 58px;
    border-radius: 4px;
    color: #ffffff;
    cursor: pointer;
    font-size: 16px;
    &:hover {
        color: #3c3c3c;
        background-color: #ffffff;
    }
    transition: all .4s;
}
.error-login-custom {
    border: 2px solid #ff0000 !important;
}
@media only screen and (min-width: $minimal) and (max-width: $medium) {
    .forgot-password-button {
        margin-left: 1%;
    }
    .forgot-input {
        margin-left: 1%;
        width: 95%;
    }
}
</style>
