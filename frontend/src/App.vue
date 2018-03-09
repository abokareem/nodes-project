<template>
  <div id="app">
    <nav-bar></nav-bar>
    <router-view></router-view>
    <foo-ter></foo-ter>
  </div>
</template>

<script>
import NavBar from '@/components/header/Header'
import FooTer from '@/components/footer/Footer'

export default {
  components: {
    NavBar,
    FooTer
  },
  name: 'App'
}
</script>

<style>
body{
  margin: 0;
  padding: 0;
}
#app {
  text-align: center;
}
</style>
