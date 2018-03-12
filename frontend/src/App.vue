<template>
    <div id="app">
        <router-view name="nav"></router-view>
        <router-view></router-view>
        <router-view name="footer"></router-view>
        <notifications>

        </notifications>
    </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
body {
    margin: 0;
    padding: 0;
}
#app {
    text-align: center;
}
</style>
