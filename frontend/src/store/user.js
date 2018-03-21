import request from '../services/axios'

const GET_USER = 'GET_USER'
const ACTIVATE_2FA = 'ACTIVATE_2FA'
const DEACTIVATE_2FA = 'DEACTIVATE_2FA'
const SET_EMAIL = 'SET_EMAIL'

const user = {
  namespaced: true,
  state: {
    user: {}
  },
  mutations: {
    [GET_USER] (state, user) {
      state.user = user
    },
    [ACTIVATE_2FA] (state) {
      state.user.two_fa = true
    },
    [DEACTIVATE_2FA] (state) {
      state.user.two_fa = false
    },
    [SET_EMAIL] (state, email) {
      state.user.email = email
    }
  },
  actions: {
    get ({commit}) {
      return new Promise((resolve, reject) => {
        request.getUser().then(res => {
          commit(GET_USER, res.data.data)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    update ({commit}, creds) {
      return new Promise((resolve, reject) => {
        request.updateUser(creds).then(res => {
          commit(GET_USER, res.data.data)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    activateTwoFa ({commit}, creds) {
      return new Promise((resolve, reject) => {
        request.activateTwoFa(creds).then(res => {
          commit(ACTIVATE_2FA)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    },
    deactivateTwoFa ({commit}, creds) {
      return new Promise((resolve, reject) => {
        request.deactivateTwoFa(creds).then(res => {
          commit(DEACTIVATE_2FA)
          resolve(res)
        }).catch(err => {
          reject(err)
        })
      })
    }
  },
  getters: {
    get: state => {
      return state.user
    }
  }
}
export default user
