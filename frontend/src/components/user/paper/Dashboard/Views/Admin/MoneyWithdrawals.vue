<template>
    <div class="admin-commissions">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        title="Заявки на вывод баланса"
                        :columns="withdrawals.columns"
                        :rows="withdrawals.data"
                        :perPage="15"
                        :paginate="true"
                        :globalSearch="true"
                        :lineNumbers="true">
                    <template slot="table-row-after" slot-scope="props">
                        <td v-if="props.row.state === $store.state.withdrawals.processing" style="width: 20%">
                            <button @click="approve(props.row)" type="button"
                                    class="btn btn-success pull-left">
                                подтвердить
                            </button>
                            <button @click="decline(props.row)" type="button"
                                    class="btn btn-danger pull-left">
                                отменить
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
import WindowModal from '../../../../../modal/Modal'
import Spinner from 'vue-spinner-component/src/Spinner.vue'

export default {
  components: {
    Spinner,
    WindowModal
  },
  data () {
    return {
      withdrawals: {
        columns: [],
        data: []
      },
      spinner: false
    }
  },
  created () {
    request.getMoneyWithdrawals(this.$i18n.locale).then(res => {
      let resWithdrawals = response.getResponse(res)
      this.withdrawals.columns = [
        {
          label: 'Кошелек для вывода',
          field: 'userWallet'
        },
        {
          label: 'Валюта',
          field: 'currency'
        },
        {
          label: 'Сумма',
          field: 'amount'
        },
        {
          label: 'Статус',
          field: 'state'
        },
        {
          label: 'Дата создания',
          field: 'created',
          type: 'date',
          inputFormat: 'YYYY-MM-DD',
          outputFormat: 'YYYY-MM-DD'
        },
        {
          label: 'Действия',
          sortable: false
        }
      ]
      for (let index in resWithdrawals) {
        this.withdrawals.data.push({
          id: resWithdrawals[index].id,
          userWallet: resWithdrawals[index].user_wallet,
          currency: resWithdrawals[index].bill.currency.name,
          amount: resWithdrawals[index].amount,
          state: resWithdrawals[index].state,
          created: resWithdrawals[index].created.date
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {
    approve (withdrawal) {
      request.approveMoneyWithdrawal(withdrawal.id, this.$i18n.locale).then(res => {
        response.handleSuccess(res, this)
        withdrawal.state = 'approve'
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    decline (withdrawal) {
      request.declineMoneyWithdrawal(withdrawal.id, this.$i18n.locale).then(res => {
        response.handleSuccess(res, this)
        withdrawal.state = 'decline'
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
