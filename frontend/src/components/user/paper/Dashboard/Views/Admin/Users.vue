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
                            <button type="button" class="btn btn-success pull-left">
                                {{$t('admin.users.buttons.edit')}}
                            </button>
                            <button v-if="!props.row.ban" type="button" class="btn btn-danger pull-left">
                                {{$t('admin.users.buttons.ban')}}
                            </button>
                            <button v-if="props.row.ban" type="button" class="btn btn-success pull-left">
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

export default {
  components: {
    Spinner
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
      let resUsers = response.getResponse(res)
      console.log(res)
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
          name: resUsers[index].name,
          email: resUsers[index].email,
          lang: resUsers[index].language,
          ban: resUsers[index].banned
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {

  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
</style>
