const response = {
  handleErrors (err, component) {
    return this[err.response.status](err.response, component)
  },
  404(err, component) {
    component.$notifications.notify({
      message: '<h3>' + err.data.data.message + '</h3>',
      icon: 'ti-bell',
      horizontalAlign: 'right',
      verticalAlign: 'bottom',
      type: 'danger',
      timeout: 2000
    })
  },
  500(err, component) {
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
