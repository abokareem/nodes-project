import axios from 'axios'

const instance = axios.create({
  baseURL: 'http://dev.nodepub.com/api'
})

const request = {
  _getGuestHeaders: {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Accept-Language': ''
    }
  },
  _getAuthHeaders: {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    }
  },
  register (creds) {
    return instance.post('/users', creds, this._getGuestHeaders)
  },
  login (creds) {
    return instance.post('/login', creds)
  },
  getUser () {
    return instance.get('/users')
  }

}
export default request
