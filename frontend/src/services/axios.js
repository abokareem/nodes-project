import axios from 'axios'

const instance = axios.create({
  baseURL: 'http://dev.nodepub.com/api'
})

const request = {
  _getGuestHeaders: {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Accept-Language': 'ru'
    }
  },
  _getAuthHeaders: {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Accept-Language': 'en',
      'Authorization': 'Bearer ' + localStorage.getItem('nodepubToken')
    }
  },
  register (creds) {
    return instance.post('/users', creds, this._getGuestHeaders)
  },
  login (creds) {
    creds.grant_type = 'password'
    creds.client_id = '3'
    creds.client_secret = '9NCPLwsB8TCIpysOLU86rBH4EJrguNpdmXvpObGA'
    creds.scope = '*'
    return instance.post('/users/auth', creds, this._getGuestHeaders)
  },
  getUser () {
    return instance.get('/users', this._getAuthHeaders)
  },
  updateUser (creds) {
    return instance.patch('/users', creds, this._getAuthHeaders)
  },
  confirmEmail (token) {
    return instance.get('/users/email/confirm/' + token)
  },
  getTwoFa () {
    return instance.get('/users/twofa', this._getAuthHeaders)
  },
  activateTwoFa (creds) {
    console.log(creds)
    return instance.post('/users/twofa', creds, this._getAuthHeaders)
  }
}
export default request
