<template>
    <div class="admin-user-bills">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        :title="$t('bills.title')"
                        :columns="bills.columns"
                        :rows="bills.data"
                        :perPage="15"
                        :paginate="true"
                        :globalSearch="true"
                        :lineNumbers="true">
                    <template slot="table-row-after" slot-scope="props">
                        <td style="width: 20%">
                            <button @click="fillInModal(props.row)" type="button" class="btn btn-success pull-left">
                                {{$t("bills.buttons.fill")}}
                            </button>
                        </td>
                    </template>
                </vue-good-table>
            </div>
        </div>
        <div @click="showBillModal = false">
            <window-modal v-if="showBillModal" @close="showBillModal = false">
                <h3 slot="header">{{$t('bills.columns.amount')}}</h3>
                <div slot="body">
                    <div class="row">
                        <input class="form-control border-input" type="number"
                               max="freePrice"
                               id="withdrawal-input"
                               v-model="amount">
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-info btn-fill btn-wd"
                                @click="fillIn({amount})">
                            {{$t("bills.buttons.fill")}}
                        </button>
                    </div>
                </div>
            </window-modal>
        </div>
    </div>
</template>
<script>
import request from '../../../../../../services/axios'
import response from '../../../../../../services/response'
import WindowModal from '../../../../../modal/Modal'

export default {
  components: {
    WindowModal
  },
  data () {
    return {
      bills: {
        columns: [],
        data: []
      },
      showBillModal: false,
      currentBill: '',
      amount: 0
    }
  },
  created () {
    request.getUsersBills(this.$i18n.locale).then(res => {
      let resBills = response.getResponse(res)
      this.bills.columns = [
        {
          label: this.$t('bills.columns.currency'),
          field: 'currency'
        },
        {
          label: this.$t('bills.columns.amount'),
          field: 'amount',
          type: 'number'
        },
        {
          label: this.$t('bills.columns.bill'),
          field: 'wallet'
        },
        {
          label: this.$t('bills.columns.actions'),
          sortable: false
        }
      ]
      for (let index in resBills) {
        this.bills.data.push({
          currency: resBills[index].currency.name,
          amount: resBills[index].amount,
          id: resBills[index].id,
          wallet: resBills[index].bill
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {
    fillInModal (bill) {
      this.showBillModal = true
      this.currentBill = bill
    },
    fillIn (amount) {
      amount.id = this.currentBill.id
      request.fillInUserBill(amount, this.$i18n.locale).then(res => {
        this.currentBill.amount = +this.currentBill.amount + +amount.amount
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
