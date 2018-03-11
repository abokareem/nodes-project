<template>
    <div @scroll="handleScroll" @resize="handleResize">
        <header class="site-custom-header">
            <!-- Navbar -->
            <div class="navbar-custom" :class="{ scrolling : scrolled, 'index-menu' : indexMenu }">
                <div class="nav-container-custom-site">
                    <!-- Logo -->
                    <router-link class="navbar-brand-custom-site" to="/">
                        <img class="logo-custom-site" alt="logo" v-bind:src="logoImage">
                    </router-link>
                    <!-- #end Logo -->
                    <div class="navbar-header-custom-site" @click="showMenu = !showMenu">
                        <span class="icon-bar-custom-site"></span>
                        <span class="icon-bar-custom-site"></span>
                        <span class="icon-bar-custom-site"></span>
                    </div>
                    <!-- MainNav -->
                    <transition name="main-dropdown-menu" v-on:after-enter="addDropdownEvent">
                        <nav v-if="showMenu" class="navbar-collapse-custom-site">
                            <ul class="navbar-nav-custom-site" v-bind:class="{ 'index-menu-ul': indexMenu }">
                                <li v-bind:class="{ 'index-menu-el': indexMenu }">
                                    <router-link to="/">
                                        {{ $t("nav.home") }}
                                    </router-link>
                                </li>
                                <li class="dropdown-custom-site" type="coins">
                                    <a class="dropdown-toggle-custom-site" type="coins">{{ $t("nav.coins") }} <b
                                            class="caret-custom-site"></b></a>
                                    <transition name="dropdown-menu">
                                        <ul class="dropdown-menu-custom-site" v-if="showCoins">
                                            <li><a href="#">Dash</a></li>
                                            <li><a href="#">Arctic</a></li>
                                            <li><a href="#">Solaris</a></li>
                                        </ul>
                                    </transition>
                                </li>
                                <li>
                                    <router-link to="/faq">
                                        {{ $t("nav.faq") }}
                                    </router-link>
                                </li>
                                <li>
                                    <router-link to="/contact">
                                        {{ $t("nav.contact") }}
                                    </router-link>
                                </li>
                                <li class="dropdown-custom-site" type="lang">
                                    <a class="dropdown-toggle-custom-site" type="lang">
                                        <img class="top-icon-custom-site" v-bind:src="langImage"> <b class="caret-custom-site"></b>
                                    </a>
                                    <transition name="dropdown-menu">
                                        <ul class="dropdown-menu-custom-site" v-if="showLang">
                                            <li v-if="langs.en">
                                                <a href="#" class="language" @click="changeLang('en')">
                                                    <img class="top-icon-custom-site"
                                                         src="../../assets/images/flags/en.svg">
                                                    <span>English</span>
                                                </a>
                                            </li>
                                            <li v-if="langs.ru">
                                                <a href="#" class="language lang-ru" @click="changeLang('ru')">
                                                    <img class="top-icon-custom-site"
                                                         src="../../assets/images/flags/ru.svg">
                                                    <span>Русский</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </transition>
                                </li>
                                <li class="quote-btn-site-custom">
                                    <router-link class="btn-site-custom btn-outline-site-custom" to="/login">
                                        {{ $t("nav.login") }}
                                    </router-link>
                                </li>
                            </ul>
                        </nav>
                    </transition>
                    <!-- #end MainNav -->
                </div>
            </div>
            <!-- End Navbar -->
        </header>
    </div>
</template>
<script>
export default{
  name: 'nav-bar',
  methods: {
    handleResize () {
      this.handleIndexPage()
      if (window.innerWidth > this.$store.state.sizes.medium) {
        this.showMenu = true
      }
    },
    handleScroll (event) {
      if (window.scrollY > 0) {
        this.scrolled = true
        this.handleIndexPage()
      } else {
        this.scrolled = false
        this.handleIndexPage()
      }
    },
    handleIndexPage () {
      if (
        this.$router.currentRoute.name === 'index' &&
        window.innerWidth > this.$store.state.sizes.medium &&
        window.scrollY === 0
      ) {
        this.indexMenu = true
        this.logoImage = require('../../assets/images/site/logo_white.png')
      } else {
        this.logoImage = require('../../assets/images/site/logo.png')
        this.indexMenu = false
      }
    },
    changeLang (lang) {
      for (let i in this.langs) {
        if (this.langs.hasOwnProperty(i)) {
          this.langs[i] = true
        }
      }
      this.$i18n.locale = lang
      this.langImage = require('../../assets/images/flags/' + lang + '.svg')
      this.langs[lang] = false
    },
    showMobileMenu () {
      if (window.innerWidth < this.$store.state.sizes.medium) {
        this.showMenu = !this.showMenu
      }
    },
    addDropdownEvent () {
      if (window.innerWidth < this.$store.state.sizes.medium) {
        let dropdowns = document.getElementsByClassName('dropdown-toggle-custom-site')
        this._setMobileDropdownEvents(dropdowns)
      } else {
        let dropdowns = document.getElementsByClassName('dropdown-custom-site')
        this._setDeckstopDropdownEvents(dropdowns)
      }
    },
    _setMobileDropdownEvents (dropdowns) {
      for (let el in dropdowns) {
        if (dropdowns.hasOwnProperty(el)) {
          const clickDropDown = () => {
            this.showCoins = dropdowns[el].type === 'coins' ? !this.showCoins : this.showCoins
            this.showLang = dropdowns[el].type === 'lang' ? !this.showLang : this.showLang
          }
          dropdowns[el].addEventListener('click', clickDropDown)
        }
      }
    },
    _setDeckstopDropdownEvents (dropdowns) {
      for (let el in dropdowns) {
        if (dropdowns.hasOwnProperty(el)) {
          const mouseOver = () => {
            this.showCoins = dropdowns[el].type === 'coins'
            this.showLang = dropdowns[el].type === 'lang'
          }
          const mouseLeave = () => {
            this.showCoins = false
            this.showLang = false
          }
          dropdowns[el].addEventListener('mouseover', mouseOver)
          dropdowns[el].addEventListener('mouseleave', mouseLeave)
        }
      }
    }
  },
  created () {
    window.addEventListener('scroll', this.handleScroll)
    window.addEventListener('resize', this.handleResize)
    this.showMobileMenu()
  },
  mounted () {
    this.handleIndexPage()
    this.changeLang('en')
    this.addDropdownEvent()
  },
  destroyed () {
    window.removeEventListener('scroll', this.handleScroll)
    window.removeEventListener('resize', this.handleResize)
  },
  data () {
    return {
      showCoins: false,
      showLang: false,
      showMenu: true,
      scrolled: false,
      indexMenu: false,
      langs: {
        ru: true,
        en: true
      },
      logoImage: require('../../assets/images/site/logo.png'),
      langImage: ''
    }
  },
  watch: {
    $route () {
      this.handleIndexPage()
    }
  }
}
</script>
<style lang="scss">
@import "style";
</style>
