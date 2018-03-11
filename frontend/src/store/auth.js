import request from '../services/axios'

const LOGIN = 'LOGIN'
const LOGIN_SUCCESS = 'LOGIN_SUCCESS'
const LOGOUT = 'LOGOUT'

const auth = {
  namespaced: true,
  state: {
    isLogged: !!localStorage.getItem('nodepubToken')
  },
  mutations: {
    [LOGIN] (state) {
      state.pending = true
    },
    [LOGIN_SUCCESS] (state) {
      state.isLoggedIn = true
      state.pending = false
    },
    [LOGOUT] (state) {
      state.isLoggedIn = false
    }
  },
  actions: {
    login ({commit}, creds) {
      commit(LOGIN)
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          request.login(creds).then(res => {
            localStorage.setItem('nodepubToken', res.data.data.access_token)
            commit(LOGIN_SUCCESS)
            resolve(res)
          }).catch(err => {
            reject(err)
          })
        }, 1000)
      })
    },

    logout ({commit}) {
      localStorage.removeItem('nodepubToken')
      commit(LOGOUT)
    }
  },
  getters: {
    isLoggedIn: state => {
      return state.isLoggedIn
    }
  }
}
export default auth
