import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth'
import user from './user'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    user
  },
  state: {
    sizes: {
      minimal: 320,
      medium: 1080,
      maximum: 1280,
      end: 1310
    },
    groups: {
      admin: 1
    },
    withdrawals: {
      processing: 'processing'
    },
    nodes: {
      unstable: 'unstable'
    },
    errors: {
      notFound: 404,
      partContent: 206
    }
  }
})

export default store
