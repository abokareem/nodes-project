<template>
    <div class="header">
      <header class="site-header is-transparent header-s1 is-sticky">
        <!-- Navbar -->
        <div class="navbar navbar-primary">
          <div class="container relative">
            <!-- Logo -->
            <a class="navbar-brand" href="">
              <img class="logo" alt="logo" src="../../assets/images/site/logo.png">
              <!--<img class="logo logo-light" alt="logo" src="../../assets/images/site/logo_white.png">-->
            </a>
            <!-- #end Logo -->
            <!--<div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mainnav" aria-expanded="false">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>-->
            <!-- MainNav -->
            <nav class="navbar-collapse collapse" id="mainnav">
              <ul class="nav navbar-nav">
                <li><a href="#">Home</a></li>
                <li class="dropdown" @mouseover="showCoins = true" @mouseleave="showCoins = false">
                    <a href="#" class="dropdown-toggle">Coins <b class="caret"></b></a>
                    <transition name="fade">
                      <ul class="dropdown-menu" v-if="showCoins">
                        <li><a href="#">Dash</a></li>
                        <li><a href="#">Arctic</a></li>
                        <li><a href="#">Solaris</a></li>
                      </ul>
                    </transition>
                </li>
                <li><a href="">FAQ</a></li>
                <li><a href="">Contact</a></li>
                <li class="dropdown" @mouseover="showLang = true" @mouseleave="showLang = false">
                  <a href="#" class="dropdown-toggle">
                    <img class="top-icon" src="../../assets/images/flags/ru.svg"> <b class="caret"></b>
                  </a>
                    <transition name="fade">
                  <ul class="dropdown-menu" v-if="showLang">
                    <li>
                      <a href="#" class="language">
                        <img class="top-icon top-dropdown-icon" src="../../assets/images/flags/us.svg">
                        <span>English</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="language">
                        <img class="top-icon top-dropdown-icon" src="../../assets/images/flags/ru.svg">
                        <span>Russian</span>
                      </a>
                    </li>
                  </ul>
                    </transition>
                </li>
                <li class="quote-btn"><a class="btn btn-outline" href="">Login</a></li>
              </ul>
            </nav>
            <!-- #end MainNav -->
          </div>
        </div>
        <!-- End Navbar -->
        <!-- Banner/Slider -->
        <div id="header" class="banner light row-vm">
          <div class="imagebg">
            <!-- <img src="../../assets/images/site/header-bg-c.jpg" alt="bg">-->
          </div>
          <div class="container">
            <div class="banner-content">
              <div class="row text-center">
                <div class="col-md-8 col-md-offset-2">
                  <h1 class="">Запуск мастерноды <br class="hidden-xs" />теперь в разы проще</h1>
                </div>
                <div class="gaps size-1x"></div>
              </div>
              <div class="row text-center">
                <div class="col-md-6 col-md-offset-3">
                  <p class="lead">NodePub - это сервис по хостингу мастернод с долевым участием</p>
                  <ul class="btns">
                    <li><a href="" class="btn">Запустить ноду</a></li><!-- Redirect to members's area if already loggen in instead of signup page-->
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Banner/Slider -->
      </header>
    </div>
</template>
<script>
export default{
  name: 'Header',
  data () {
    return {
      showCoins: false,
      showLang: false
    }
  }
}
</script>
<style lang="scss">
@import "style";
</style>
