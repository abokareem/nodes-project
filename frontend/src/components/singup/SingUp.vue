<template>
    <div class="sing-up">
        <div class="sing-up-container">
            <h3 class="sing-up-heading-lead center">{{ $t("singUp.title") }}</h3>
            <form class="sing-up-form" @submit.prevent="register({ email, password, name, subscribe })">
                <div class="sing-up-data-container">
                    <input type="text" placeholder="Name *" v-model="name" class="sing-up-name-input sing-up-input">
                </div>
                <div class="sing-up-data-container">
                    <input type="email" placeholder="Email *" v-model="email" class="sing-up-email-input sing-up-input">
                </div>
                <div class="sing-up-data-container">
                    <input type="password" placeholder="Password *" v-model="password"
                           class="sing-up-password-input sing-up-input">
                </div>
                <div class="sing-up-data-container">
                    <input type="password" placeholder="Confirm Password *"
                           class="sing-up-password-input sing-up-input">
                </div>
                <div class="sing-up-captcha-container">
                    <captcha></captcha>
                </div>
                <div class="sing-up-data-container">
                    <div class="sing-up-term-container">
                        <input type="checkbox" v-model="subscribe" name="sign-up-term">
                        <span>{{ $t("singUp.terms.text") }}
                        <a href="#">{{ $t("singUp.terms.url") }}</a>.
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

export default {
  components: {
    captcha
  },
  name: 'sing-up',
  methods: {
    register (creds) {
      const lang = navigator.language || navigator.userLanguage
      creds.language = lang.substring(0, 2)
      request.register(creds).then(res => {
        console.log(res)
      }).catch(err => {
        console.log(err)
      })
    }
  },
  data () {
    return {
      name: '',
      email: '',
      password: '',
      subscribe: ''
    }
  }
}
</script>
<style lang="scss">
@import "../../assets/partials/_const.scss";
@mixin sing-up-placeholder-styles {
    color: #c3c3c3;
    font-family: "Quicksand", sans-serif;
    font-size: 16px;
    padding-left: 5px;
}
@mixin sing-up-elements {
    display: inline-block;
    margin-left: 32%;
}
@mixin sing-up-elements-mobile {
    display: inline-block;
    margin-left: 0;
}
.sing-up {
    display: inline-block;
    width: 100%;
    padding-top: 135px;
    padding-bottom: 50px;
    min-height: calc(100vh - 188px);
}
.sing-up-container {
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
.sing-up-heading-lead {
    font-size: 2.5em;
    font-weight: 500;
    font-family: "Quicksand", sans-serif;
}
/**/
.sing-up-data-container, .sing-up-redirect-container, .sing-up-button-container, .sing-up-captcha-container {
    width: 100%;
    text-align: left;
}
.sing-up-input {
    @include sing-up-elements;
    height: 45px;
    margin-bottom: 14px;
    background: #f8f8f8;
    padding: 6px 12px;
    font-size: 14px;
    color: #555;
    border: 1px solid #e1e1e1;
    border-radius: 4px;
    width: 600px;
    &::-webkit-input-placeholder {
        @include sing-up-placeholder-styles;
    }
    &::-moz-placeholder {
        @include sing-up-placeholder-styles;
    }
    &:-moz-placeholder {
        @include sing-up-placeholder-styles;
    }
    &:-ms-input-placeholder {
        @include sing-up-placeholder-styles;
    }
}
.sing-up-captcha, .sing-up-button, .sing-up-redirect-register, .sing-up-term-container {
    @include sing-up-elements;
}
.sing-up-term-container {
    margin-top: 15px;
    margin-bottom: 15px;
    font-size: 18px;
    input {
        zoom: 1.5;
    }
    a {
        margin-left: 20px;
        text-decoration: none;
        color: #f7921a;
    }
}
.sing-up-button {
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
.sing-up-redirect-container {
    margin-top: 10px;
    .sing-up-redirect-register {
        font-size: 14px;
        a {
            color: #f7921a;
            text-decoration: none;
            margin-left: 15px;
        }
    }
}
@media only screen and (min-width: $minimal) and (max-width: $medium) {
    .sing-up-form {
        .sing-up-input {
            @include sing-up-elements-mobile;
            width: 95%;
        }
        .sing-up-captcha, .sing-up-button, .sing-up-redirect-register {
            @include sing-up-elements-mobile;
        }
    }
}
</style>
