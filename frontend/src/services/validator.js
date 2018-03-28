const emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
const passwordRegExp = /^\S.{6,20}$/
const nameRegExp = /^\S.{1,20}$/
const twoFaRegExp = /^\S+$/
const walletRegExp = /^\S+$/
const messageRegExp = /^\S+$/

const validator = {
  email (email) {
    let result = emailRegExp.test(email)

    if (result) {
      return true
    } else {
      return false
    }
  },

  password (password) {
    let result = passwordRegExp.test(password)

    if (result) {
      return true
    } else {
      return false
    }
  },
  passwordConfirm (password, confirmed) {
    if (password === confirmed) {
      return true
    } else {
      return false
    }
  },
  name (name) {
    let result = nameRegExp.test(name)

    if (result) {
      return true
    } else {
      return false
    }
  },
  twoFaCode (code) {
    let result = twoFaRegExp.test(code)

    if (result) {
      return true
    } else {
      return false
    }
  },
  sharesCount (shares, freeshares) {
    if (shares < 1 || shares > freeshares) {
      return false
    }
    return true
  },
  wallet (wallet) {
    let result = walletRegExp.test(wallet)

    if (result) {
      return true
    } else {
      return false
    }
  },
  withdrawalUserAmount (price, existPrice) {
    if (price === 0) {
      return false
    }
    if (+existPrice < price) {
      return false
    }
    return true
  },
  contactUsMessage (message) {
    let result = messageRegExp.test(message)

    if (result) {
      return true
    } else {
      return false
    }
  }
}

export default validator
