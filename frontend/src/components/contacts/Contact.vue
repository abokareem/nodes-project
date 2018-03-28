<template>
    <div class="contact">
        <div class="contact-banner-container">
            <h2>{{ $t("contact.us") }}</h2>
            <div class="contact-banner-content-container">
                <ul>
                    <li>
                        <router-link to="/">{{ $t("contact.home") }}</router-link>
                    </li>
                    <li class="active"><span>{{ $t("contact.us") }}</span></li>
                </ul>
            </div>
        </div>
        <!-- Section -->
        <div class="contact-form-container">
            <div class="contact-form-inside-container">
                <p>{{ $t("contact.desc") }}</p>
                <form>
                    <div class="contact-form-container-top-fields">
                        <div>
                            <input name="name" type="text" placeholder="Name *" v-model="name">
                        </div>
                        <div>
                            <input name="email" type="email" placeholder="Email *" v-model="email"
                                   :class="{'error-contact-custom':!isValidEmail}">
                            <span class="error-contact-span-custom" v-if="!isValidEmail">
                                {{$t("validate.email")}}
                            </span>
                        </div>
                    </div>
                    <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="spinner" :size="60"></spinner>
                    <div class="contact-form-subject-container">
                        <input name="subject" type="text" placeholder="Subject *" v-model="subject">
                    </div>
                    <div class="contact-form-message-container">
                        <textarea name="message" placeholder="Messages *" v-model="message"
                                  :class="{'error-contact-custom':!isValidMessage}"></textarea>
                        <span class="error-contact-span-custom" v-if="!isValidMessage">
                                {{$t("validate.contactMessage")}}
                        </span>
                    </div>
                    <div class="contact-form-captcha-container">
                        <captcha></captcha>
                    </div>
                    <button type="submit" class="contact-form-button"
                            @click.prevent="send({name,email,subject,message})">
                        {{ $t("contact.button") }}
                    </button>
                </form>
            </div>
        </div>
        <!-- End Section -->
    </div>
</template>
<script>
import captcha from '../captcha/Captcha.vue'
import validator from '../../services/validator'
import request from '../../services/axios'
import response from '../../services/response'
import Spinner from 'vue-spinner-component/src/Spinner.vue'

export default {
  components: {
    Spinner,
    captcha
  },
  name: 'Contact',
  methods: {
    send (data) {
      this.isValidEmail = validator.email(this.email)
      this.isValidMessage = validator.contactUsMessage(this.message)
      if (this.isValidEmail && this.isValidMessage) {
        this.spinner = true
        request.sendContactForm(data, this.$i18n.locale).then(res => {
          response.handleSuccess(res, this)
          this.spinner = false
        }).catch(err => {
          response.handleErrors(err, this)
          this.spinner = false
        })
      }
    }
  },
  watch: {
    email () {
      this.isValidEmail = validator.email(this.email)
    },
    message () {
      this.isValidMessage = validator.contactUsMessage(this.message)
    }
  },
  data () {
    return {
      name: '',
      email: '',
      message: '',
      subject: '',
      isValidMessage: true,
      isValidEmail: true,
      spinner: false
    }
  }
}
</script>
<style lang="scss">
@import "style";
</style>
