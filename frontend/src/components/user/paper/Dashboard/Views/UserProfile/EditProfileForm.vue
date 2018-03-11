<template>
    <div class="card">
        <div class="header">
            <h4 class="title">Edit Profile</h4>
        </div>
        <div class="content">
            <form>
                <div class="row">
                    <div class="col-md-4">

                        <fg-input type="text"
                                  label="Username"
                                  placeholder="Username"
                                  v-model="user.name">
                        </fg-input>
                    </div>
                    <div class="col-md-4">
                        <fg-input type="email"
                                  label="Email"
                                  placeholder="Email"
                                  v-model="user.email">
                        </fg-input>
                    </div>
                    <div class="col-md-4">
                        <fg-input type="password"
                                  label="Password"
                                  placeholder="Password"
                                  v-model="user.password">
                        </fg-input>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-info btn-fill btn-wd" @click.prevent="updateProfile">
                        Update Profile
                    </button>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
  data () {
    return {
      user: {
        name: this.$store.getters['user/get'].name,
        email: this.$store.getters['user/get'].email,
        password: ''
      }
    }
  },
  created () {
    let user = this.$store.getters['user/get']
    this.user.name = user.name
    this.user.email = user.email
  },
  methods: {
    updateProfile () {
      if (this.user.password === '') {
        delete this.user.password
      }
      if (this.user.email === this.$store.getters['user/get'].email) {
        delete this.user.email
      }
      this.$store.dispatch('user/update', this.user).then(res => {
        this.$notifications.notify(
          {
            message: 'Profile was updated.',
            icon: 'ti-gift',
            horizontalAlign: 'right',
            verticalAlign: 'bottom',
            type: 'info',
            timeout: 2000
          })
      }).catch(err => {
        console.log(err.response)
      })
    }
  }
}

</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
</style>
