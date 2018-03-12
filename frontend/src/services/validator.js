const emailRegExp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
const passwordRegExp = /^\S.{6,20}$/
const nameRegExp = /^\S.{2,20}$/

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
  }
}

export default validator
