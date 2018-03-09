<template>
    <div class="index">
        <!-- Banner/Slider -->
        <div class="index-banner-container">
            <div class="index-banner-data-container">
                <div class="index-container-title">
                    <h1>{{ $t("index.banner.title") }}</h1>
                </div>
                <div class="index-container-description">
                    <p class="lead">{{ $t("index.banner.description") }}</p>
                </div>
                <router-link class="index-start-node-button" to="/singup">
                    {{ $t("index.banner.button") }}
                </router-link>
                <!-- Redirect to members's area if already loggen in instead of signup page-->
            </div>
        </div>
        <!-- End Banner/Slider -->
        <!-- Features Box -->
        <div class="index-box-container">
            <div class="index-box-data-container">
                <div class="image-icon dots-a">
                    <img src="../../assets/images/site/box-icon-i.png" alt="box-icon">
                </div>
                <h5>{{ $t("index.boxes.choose.title") }}</h5>
                <p>{{ $t("index.boxes.choose.desc") }}</p>
            </div>
            <div class="index-box-data-container">
                <div class="image-icon dots-b">
                    <img src="../../assets/images/site/box-icon-j.png" alt="box-icon">
                </div>
                <h5>{{ $t("index.boxes.buy.title") }}</h5>
                <p>{{ $t("index.boxes.buy.desc") }}</p>
            </div>
            <div class="index-box-data-container">
                <div class="image-icon">
                    <img src="../../assets/images/site/box-icon-k.png" alt="box-icon">
                </div>
                <h5>{{ $t("index.boxes.get.title") }}</h5>
                <p>{{ $t("index.boxes.get.desc") }}</p>
            </div>
        </div>
        <!--End Features Box -->
        <!--Section -->
        <div class="index-new-coin-container">
            <div class="new-coin-main-container">
                <div class="index-inside-new-coin-container">
                    <div class="index-new-coin-image-container">
                        <img src="../../assets/images/site/illustration-md-a.png" alt="photo-md">
                    </div>
                    <div class="index-new-coin-text">
                        <h2>{{ $t("index.newCoin.title") }}</h2>
                        <p>{{ $t("index.newCoin.desc") }}</p>
                        <a href="#" class="btn btn-alt">{{ $t("index.newCoin.button") }}</a>
                    </div>
                </div>
            </div>
        </div>
        <!--End Section -->
        <!--Section -->
        <div class="index-advantages-container">
            <div class="index-advantages-title-container">
                <h2>{{ $t("index.advantages.title.h") }}</h2>
                <p>{{ $t("index.advantages.title.p") }}</p>
            </div>
            <div class="index-advantages-content-container">
                <div>
                    <div>
                        <span class="font-icon">
                            <span class="index-advantages-pe pe-7s-lock"></span>
                        </span>
                        <h4>{{ $t("index.advantages.blocks.first.title") }}</h4>
                        <p>{{ $t("index.advantages.blocks.first.desc") }}</p>
                    </div>
                </div>
                <div>
                    <div>
                    <span class="font-icon">
                        <span class="index-advantages-pe pe-7s-repeat"></span>
                    </span>
                        <h4>{{ $t("index.advantages.blocks.second.title") }}</h4>
                        <p>{{ $t("index.advantages.blocks.second.desc") }}</p>
                    </div>
                </div>
                <div>
                    <div>
                    <span class="font-icon">
                        <span class="index-advantages-pe pe-7s-graph2"></span>
                    </span>
                        <h4>{{ $t("index.advantages.blocks.third.title") }}</h4>
                        <p>{{ $t("index.advantages.blocks.third.desc") }}</p>
                    </div>
                </div>
                <div>
                    <div>
                    <span class="font-icon">
                        <span class="index-advantages-pe pe-7s-cash"></span>
                    </span>
                        <h4>{{ $t("index.advantages.blocks.fourth.title") }}</h4>
                        <p>{{ $t("index.advantages.blocks.fourth.desc") }}</p>
                    </div>
                </div>
                <div>
                    <div>
                    <span class="font-icon">
                        <span class="index-advantages-pe pe-7s-global"></span>
                    </span>
                        <h4>{{ $t("index.advantages.blocks.five.title") }}</h4>
                        <p>{{ $t("index.advantages.blocks.five.desc") }}</p>
                    </div>
                </div>
                <div>
                    <div>
                    <span class="font-icon">
                        <span class="index-advantages-pe pe-7s-wallet"></span>
                    </span>
                        <h4>{{ $t("index.advantages.blocks.sixth.title") }}</h4>
                        <p>{{ $t("index.advantages.blocks.sixth.desc") }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!--End Section -->
        <!-- Section -->
        <div class="index-mobile-link-container">
            <div>
                <h2>{{ $t("index.mobile.url.title") }}</h2>
                <p>{{ $t("index.mobile.url.desc") }}</p>
                <a class="android" href="#">
                    <img src="../../assets/images/site/android.png" alt="android">
                </a>
                <a href="#">
                    <img src="../../assets/images/site/apple.png" alt="apple">
                </a>
            </div>
        </div>
        <!-- End Section -->
        <!--Section -->
        <div class="index-mobile-data-container">
            <div class="index-mobile-data-inside-container">
                <div class="index-mobile-data-image-container">
                    <img src="../../assets/images/site/illustration-md-b.png" alt="photo-md">
                </div>
                <div class="index-mobile-data-text-container">
                    <div>
                        <h2>{{ $t("index.mobile.text.title") }}</h2>
                        <p>{{ $t("index.mobile.text.desc") }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!--End Section -->
        <!-- Section -->
        <div class="index-start-node-container">
            <div class="index-start-node-inside-container">
                <div>
                    <h3>{{ $t("index.startNode.title") }}</h3>
                </div>
                <div class="index-start-node-link-container">
                    <a href="#">{{ $t("index.startNode.button") }}</a>
                </div>
            </div>
        </div>
        <!-- End Section -->
        <!--move-->
        <div class="index-company-container">
            <swiper :options="swiperOption">
                <swiper-slide v-for="slide in slides" :key='slide'>
                    <img alt="" width="190" height="82" :src="slide">
                </swiper-slide>
                <div class="swiper-pagination" slot="pagination"></div>
            </swiper>
        </div>
    </div>
</template>
<script>
import 'swiper/dist/css/swiper.css'
import { swiper, swiperSlide } from 'vue-awesome-swiper'

export default{
  components: {
    swiper,
    swiperSlide
  },
  name: 'Index',
  beforeMount () {
    if (window.innerWidth < this.$store.state.sizes.medium) {
      this.swiperOption.slidesPerView = 3
    }
  },
  data () {
    return {
      swiperOption: {
        slidesPerView: 6,
        spaceBetween: 10,
        slidesPerGroup: 1,
        loop: true,
        loopFillGroupWithBlank: true
      },
      slides: [
        require('../../assets/images/site/cl-logo1.png'),
        require('../../assets/images/site/cl-logo2.png'),
        require('../../assets/images/site/cl-logo3.png'),
        require('../../assets/images/site/cl-logo4.png'),
        require('../../assets/images/site/cl-logo5.png'),
        require('../../assets/images/site/cl-logo6.png')
      ]
    }
  }
}
</script>
<style lang="scss">
@import "style.scss";
</style>
