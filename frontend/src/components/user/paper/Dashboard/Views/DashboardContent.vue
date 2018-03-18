<template>
    <div class="dashboard-content">
        <div class="card col-md-4 pull-right">
            <div class="header">
                <h4 class="title">Actions</h4>
            </div>
            <div v-if="user.actions" class="content">
                <div class="row">
                    <ul class="list-group list-group-flush">
                        <li v-for="action in user.actions" :key="action" class="list-group-item">
                            {{action.message}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import request from '../../../../../services/axios'
import response from '../../../../../services/response'

export default{
  created () {
    request.getUserActions().then(res => {
      this.user.actions = response.getResponse(res)
    })
  },
  data () {
    return {
      user: {
        actions: false
      }
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../assets/sass/bootstrap.css";
@import "../../../../../assets/sass/paper-dashboard.scss";
</style>
