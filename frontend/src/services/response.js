const response = {
  handleErrors (err, component) {
    return this[err.response.status](err.response, component)
  },
  handleSuccess (success, component) {
    return this[success.status](success, component)
  },
  getResponse (res) {
    return res.data.data
  },
  200 (success, component) {
    component.$notifications.notify({
      message: '<h3>' + success.data.message + '</h3>',
      icon: 'ti-bell',
      horizontalAlign: 'right',
      verticalAlign: 'bottom',
      type: 'success',
      timeout: 2000
    })
  },
  401 (err, component) {
    component.$router.push('/login')
    component.$notifications.notify({
      message: '<h3>' + err.data.message + '</h3>',
      icon: 'ti-bell',
      horizontalAlign: 'right',
      verticalAlign: 'bottom',
      type: 'danger',
      timeout: 2000
    })
  },
  403 (err, component) {
    component.$notifications.notify({
      message: '<h3>' + err.data.message + '</h3>',
      icon: 'ti-bell',
      horizontalAlign: 'right',
      verticalAlign: 'bottom',
      type: 'danger',
      timeout: 2000
    })
  },
  404 (err, component) {
    component.$router.push('/notfound')
  },
  500 (err, component) {
    component.$notifications.notify({
      message: '<h3>' + err.data.message + '</h3>',
      icon: 'ti-bell',
      horizontalAlign: 'right',
      verticalAlign: 'bottom',
      type: 'danger',
      timeout: 2000
    })
  }
}

export default response
