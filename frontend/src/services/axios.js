import axios from 'axios'

const instance = axios.create({
  baseURL: 'http://dev.nodepub.com/api'
})

const request = {
  _getGuestHeaders (lang) {
    return {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Accept-Language': lang
      }
    }
  },
  _getAuthHeaders (lang) {
    return {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Accept-Language': lang,
        'Authorization': 'Bearer ' + localStorage.getItem('nodepubToken')
      }
    }
  },
  register (creds, lang) {
    return instance.post('/users', creds, this._getGuestHeaders(lang))
  },
  login (creds, lang) {
    creds.grant_type = 'password'
    creds.client_id = '1'
    creds.client_secret = 'Q6nU8HHpUXb0xfJJaNc4G88jmBwFtWpFfpVy4VdR'
    creds.scope = '*'
    return instance.post('/users/auth', creds, this._getGuestHeaders(lang))
  },
  login2fa (creds, lang) {
    return instance.post('/users/auth/twofa', creds, this._getGuestHeaders(lang))
  },
  getUser (lang) {
    return instance.get('/users', this._getAuthHeaders(lang))
  },
  updateUser (creds, lang) {
    return instance.patch('/users', creds, this._getAuthHeaders(lang))
  },
  confirmEmail (token, lang) {
    return instance.get('/users/email/confirm/' + token, this._getGuestHeaders(lang))
  },
  getTwoFa (lang) {
    return instance.get('/users/twofa', this._getAuthHeaders(lang))
  },
  getUserActions (lang) {
    return instance.get('/users/actions', this._getAuthHeaders(lang))
  },
  activateTwoFa (creds, lang) {
    return instance.post('/users/twofa', creds, this._getAuthHeaders(lang))
  },
  deactivateTwoFa (creds, lang) {
    return instance.delete('/users/twofa',
      {
        headers: this._getAuthHeaders(lang).headers,
        data: creds
      })
  },
  checkCodeTwoFa (creds, lang) {
    return instance.post('/users/twofa/auth', creds, this._getAuthHeaders(lang))
  },
  forgotPassword (creds, lang) {
    return instance.post('/users/password', creds, this._getGuestHeaders(lang))
  },
  resetPassword (creds, lang) {
    return instance.patch('/users/password', creds, this._getGuestHeaders(lang))
  },
  resendEmail (lang) {
    return instance.get('/users/email/resend', this._getAuthHeaders(lang))
  },
  getUserNodes (lang) {
    return instance.get('/users/nodes', this._getAuthHeaders(lang))
  },
  getUserTransactions (lang) {
    return instance.get('/users/transactions', this._getAuthHeaders(lang))
  },
  getUserWithdrawals (lang) {
    return instance.get('/users/withdrawals', this._getAuthHeaders(lang))
  },
  withdrawalNode (creds, lang) {
    return instance.post('/withdrawals', creds, this._getAuthHeaders(lang))
  },
  declineWithdrawal (id, lang) {
    return instance.delete('/withdrawals/decline/' + id, this._getAuthHeaders(lang))
  },
  getNodes (lang) {
    return instance.get('/nodes', this._getAuthHeaders(lang))
  },
  getCurrencies (lang) {
    return instance.get('/currency', this._getGuestHeaders(lang))
  },
  createNode (data, lang) {
    return instance.post('/nodes', data, this._getAuthHeaders(lang))
  },
  buyShares (data, lang) {
    return instance.post('/shares/buy', data, this._getAuthHeaders(lang))
  },
  withdrawalMoney (data, lang) {
    return instance.delete('/money', {
      headers: this._getAuthHeaders(lang).headers,
      data: data
    })
  },
  getNodesWithdrawals (id, lang) {
    return instance.get('/nodes/' + id + '/withdrawals', this._getAuthHeaders(lang))
  },
  buyNodesWithdrawal (id, lang) {
    return instance.post('/withdrawals/buy/' + id, null, this._getAuthHeaders(lang))
  },
  createBill (data, lang) {
    return instance.post('/money', data, this._getAuthHeaders(lang))
  },
  getUsersBills (lang) {
    return instance.get('/bills', this._getAuthHeaders(lang))
  },
  fillInUserBill (data, lang) {
    return instance.patch('/bills/' + data.id, data, this._getAuthHeaders(lang))
  },
  sendContactForm (data, lang) {
    return instance.post('/contact', data, this._getAuthHeaders(lang))
  },
  adminGetUsers (lang) {
    return instance.get('/admin/users', this._getAuthHeaders(lang))
  },
  getCommissions (lang) {
    return instance.get('/admin/commissions', this._getAuthHeaders(lang))
  },
  changeCommission (data, lang) {
    return instance.patch('/admin/commissions/' + data.id, data, this._getAuthHeaders(lang))
  },
  softDeleteUser (id, lang) {
    return instance.delete('/admin/users/' + id, this._getAuthHeaders(lang))
  },
  restoreUser (id, lang) {
    return instance.put('/admin/users/' + id, '', this._getAuthHeaders(lang))
  },
  adminUpdateUser (data, lang) {
    return instance.patch('/admin/users/' + data.id, data, this._getAuthHeaders(lang))
  },
  getAdminNodes (lang) {
    return instance.get('/admin/nodes', this._getAuthHeaders(lang))
  },
  setProfit (data, lang) {
    return instance.post('/admin/nodes/' + data.id + '/profits', data, this._getAuthHeaders(lang))
  },
  getAdminCurrencies (lang) {
    return instance.get('/admin/currency', this._getAuthHeaders(lang))
  },
  editShare (data, lang) {
    return instance.patch('/shares/' + data.shareId, data, this._getAuthHeaders(lang))
  },
  editCurrency (data, lang) {
    return instance.patch('/currency/' + data.currencyId, data, this._getAuthHeaders(lang))
  },
  createCurrency (data, lang) {
    return instance.post('/currency', data, this._getAuthHeaders(lang))
  },
  createShare (data, lang) {
    return instance.post('/shares/' + data.id, data, this._getAuthHeaders(lang))
  },
  adminGetAllNodeWithdrawals (lang) {
    return instance.get('/admin/withdrawals', this._getAuthHeaders(lang))
  },
  approveNodeWithdrawal (id, lang) {
    return instance.patch('/withdrawals/approve/' + id, null, this._getAuthHeaders(lang))
  },
  loadFreeWallets (data, lang) {
    return instance.post('/systems/wallets', data, this._getAuthHeaders(lang))
  },
  getMoneyWithdrawals (lang) {
    return instance.get('/admin/money/withdrawals', this._getAuthHeaders(lang))
  },
  approveMoneyWithdrawal (id, lang) {
    return instance.patch('/money/approve/' + id, null, this._getAuthHeaders(lang))
  },
  declineMoneyWithdrawal (id, lang) {
    return instance.delete('/money/decline/' + id, this._getAuthHeaders(lang))
  }
}

export default request
