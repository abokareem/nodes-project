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
                            <input name="name" type="text" placeholder="Name *">
                        </div>
                        <div>
                            <input name="email" type="email" placeholder="Email *">
                        </div>
                    </div>
                    <div class="contact-form-subject-container">
                        <input name="subject" type="text" placeholder="Subject *">
                    </div>
                    <div class="contact-form-message-container">
                        <textarea name="message" placeholder="Messages *"></textarea>
                    </div>
                    <div class="form-field col-md-12">
                        <!--<captcha></captcha>-->
                    </div>
                    <button type="submit" class="contact-form-button">{{ $t("contact.button") }}</button>
                </form>
            </div>
        </div>
        <!-- End Section -->
    </div>
</template>
<script>
import captcha from '../captcha/Captcha.vue'

export default {
  components: {
    captcha
  },
  name: 'Contact'
}
</script>
<style lang="scss">
@import "style";
</style>
