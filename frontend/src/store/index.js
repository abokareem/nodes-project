import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    result: ['hello'],
    sizes: {
      minimal: 320,
      medium: 1080,
      maximum: 1280,
      end: 1310
    }
  }
})

export default store
