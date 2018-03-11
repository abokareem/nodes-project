import request from '../services/axios'

const GET_USER = 'GET_USER'

const user = {
  namespaced: true,
  state: {
    user: {}
  },
  mutations: {
    [GET_USER] (state, user) {
      state.user = user
    }
  },
  actions: {
    get ({commit}) {
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          request.getUser().then(res => {
            commit(GET_USER, res.data.data)
            resolve(res)
          }).catch(err => {
            reject(err)
          })
        }, 1000)
      })
    },
    update ({commit}, creds) {
      return new Promise((resolve, reject) => {
        setTimeout(() => {
          request.updateUser(creds).then(res => {
            commit(GET_USER, res.data.data)
            resolve(res)
          }).catch(err => {
            reject(err)
          })
        }, 1000)
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
