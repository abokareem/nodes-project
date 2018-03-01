<template>
  <div id="app">
    <Header></Header>
  </div>
</template>

<script>
import Header from '@/components/header/Header'

export default {
  components: {
    Header
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
