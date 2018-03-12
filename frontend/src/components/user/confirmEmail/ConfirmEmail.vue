<template>
    <div>
    </div>
</template>
<script>
import request from '../../../services/axios'

export default{
  name: 'ConfirmEmail',
  created () {
    request.confirmEmail(this.$route.params.token).then(res => {
      this.$router.push('/login')
      this.$notifications.notify({
        message: '<h3>' + res.data.message + '</h3>',
        icon: 'ti-bell',
        horizontalAlign: 'right',
        verticalAlign: 'bottom',
        type: 'success',
        timeout: 2000
      })
    }).catch(err => {
      if (this.$store.state.errors.notFound === err.response.status) {
        this.$router.push('/notFound')
      }
    })
  }
}
</script>
