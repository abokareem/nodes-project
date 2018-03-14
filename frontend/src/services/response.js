const response = {
  handleErrors (err) {
    return this[err.response.status](err.response)
  },
  404 (err) {

  }
}

export default response
