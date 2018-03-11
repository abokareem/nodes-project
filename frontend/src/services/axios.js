import axios from 'axios'

const instance = axios.create({
  baseURL: 'http://dev.nodepub.com/api'
})

const request = {
  _getGuestHeaders: {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Accept-Language': 'en'
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
    console.log(creds)
    return instance.patch('/users', creds, this._getAuthHeaders)
  }

}
export default request
