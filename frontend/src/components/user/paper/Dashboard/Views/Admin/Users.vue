<template>
    <div class="admin-users">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        :title="$t('admin.users.title')"
                        :columns="users.columns"
                        :rows="users.data"
                        :perPage="15"
                        :paginate="true"
                        :globalSearch="true"
                        :lineNumbers="true">
                    <template slot="table-row-after" slot-scope="props">
                        <td style="width: 20%">
                            <user-edit :user="props.row"></user-edit>
                            <button @click="softDelete(props.row)" v-if="!props.row.ban" type="button"
                                    class="btn btn-danger">
                                {{$t('admin.users.buttons.ban')}}
                            </button>
                            <button @click="restore(props.row)" v-if="props.row.ban" type="button"
                                    class="btn btn-success">
                                {{$t('admin.users.buttons.unban')}}
                            </button>
                        </td>
                    </template>
                </vue-good-table>
            </div>
        </div>
    </div>
</template>
<script>
import request from '../../../../../../services/axios'
import response from '../../../../../../services/response'
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import UserEdit from './Modals/UserEdit.vue'

export default {
  components: {
    Spinner,
    UserEdit
  },
  data () {
    return {
      users: {
        columns: [],
        data: []
      },
      showBillModal: false,
      currentBill: '',
      amount: 0,
      spinner: false
    }
  },
  created () {
    request.adminGetUsers(this.$i18n.locale).then(res => {
      console.log(res)
      let resUsers = response.getResponse(res)
      this.users.columns = [
        {
          label: this.$t('admin.users.columns.name'),
          field: 'name'
        },
        {
          label: this.$t('admin.users.columns.email'),
          field: 'email'
        },
        {
          label: this.$t('admin.users.columns.lang'),
          field: 'lang'
        },
        {
          label: this.$t('admin.columns.actions'),
          sortable: false
        }
      ]
      for (let index in resUsers) {
        this.users.data.push({
          id: resUsers[index].id,
          name: resUsers[index].name,
          email: resUsers[index].email,
          lang: resUsers[index].language,
          group: resUsers[index].group,
          ban: resUsers[index].banned
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {
    restore (user) {
      request.restoreUser(user.id, this.$i18n.locale).then(res => {
        let data = response.getResponse(res)
        user.ban = data.banned
        response.handleSuccess(res, this)
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    softDelete (user) {
      request.softDeleteUser(user.id, this.$i18n.locale).then(res => {
        let data = response.getResponse(res)
        user.ban = data.banned
        response.handleSuccess(res, this)
      }).catch(err => {
        response.handleErrors(err, this)
      })
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
</style>
