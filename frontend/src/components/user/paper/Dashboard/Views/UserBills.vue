<template>
    <div class="user-bill-container">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        title="Bills"
                        :columns="bills.columns"
                        :rows="bills.data"
                        :perPage="15"
                        :paginate="true"
                        :globalSearch="true"
                        :lineNumbers="true">
                    <template slot="table-row-after" slot-scope="props">
                        <td style="width: 20%">
                            <button v-if="props.row.isBill"
                                    @click="fillIn(props.row.id)" type="button" class="btn btn-success pull-left">
                                fill in
                            </button>
                            <button v-if="props.row.isBill"
                                    @click="withdrawalMoneyModal(props.row.id)" type="button"
                                    class="btn btn-danger pull-right">
                                Withdrawal
                            </button>
                            <button v-if="!props.row.isBill"
                                    @click="createBill(props.row.id)" type="button" class="btn btn-success">
                                Create bill
                            </button>
                        </td>
                    </template>
                </vue-good-table>
            </div>
        </div>
        <div @click="showWithdrawalModal = false">
            <window-modal v-if="showWithdrawalModal" @close="showWithdrawalModal = false">
                <h3 slot="header">Withdrawal Money</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <h4 v-if="hash" class="title">{{$t("twofa.activate.key")}}</h4>
                        <div class="text-center">
                            <label for="wallet-input">
                                Enter your Wallet
                            </label>
                            <input class="form-control border-input"
                                   type="text"
                                   id="wallet-input"
                                   :placeholder="$t('twofa.code') + '*'"
                                   v-model="wallet"
                                   :class="{'error-wallet-custom':!isValidWallet}">
                            <span class="error-wallet-validate" v-if="!isValidWallet">
                                {{$t("validate.wallet")}}
                            </span>
                            <label for="withdrawal-input">
                                Amount for withdrawal
                            </label>
                            <input class="form-control border-input" type="number"
                                   :max="freePrice"
                                   id="withdrawal-input"
                                   v-model="price"
                                   :class="{'error-create-node':!isValidPrice}">
                            <span class="error-create-node-validate"
                                  v-if="!isValidPrice">{{$t("validate.shares") + freePrice}}</span>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-danger btn-fill btn-wd"
                                @click="withdrawalMoney({wallet, price})">
                            Withdrawal
                        </button>
                    </div>
                </div>
            </window-modal>
        </div>
    </div>
</template>
<script>
import request from '../../../../../services/axios'
import response from '../../../../../services/response'
import WindowModal from '../../../../modal/Modal'
import Spinner from 'vue-spinner-component/src/Spinner.vue'

export default {
  components: {
    Spinner,
    WindowModal
  },
  data () {
    return {
      bills: {
        columns: [],
        data: []
      },
      showWithdrawalModal: false,
      isValidWallet: true,
      isValidPrice: true,
      price: 0,
      wallet: '',
      id: ''
    }
  },
  created () {
    request.getUser(this.$i18n.locale).then(res => {
      let resBills = response.getResponse(res).bills
      let existBill = []
      this.bills.columns = [
        {
          label: this.$t('dashboard.columns.currency'),
          field: 'currency'
        },
        {
          label: this.$t('dashboard.columns.amount'),
          field: 'amount',
          type: 'number'
        },
        {
          label: 'Actions',
          sortable: false
        }
      ]
      for (let index in resBills) {
        this.bills.data.push({
          currency: resBills[index].currency.name,
          amount: resBills[index].amount,
          isBill: true
        })
        existBill.push(resBills[index].currency.id)
      }
      request.getCurrencies(this.$i18n.locale).then(res => {
        let currencies = response.getResponse(res)
        for (let index in currencies) {
          if (existBill.indexOf(currencies[index].id) === -1) {
            this.bills.data.push({
              currency: currencies[index].name,
              isBill: false
            })
          }
        }
      }).catch(err => {
        response.handleErrors(err, this)
      })
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {
    withdrawalMoneyModal (id) {
      this.showWithdrawalModal = true
      this.id = id
    },
    withdrawalMoney (data) {
      data.bill_id = this.id
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../assets/sass/bootstrap.css";
@import "../../../../../assets/sass/paper-dashboard.scss";
input {
    margin-bottom: 10px;
}
.error-wallet-validate {
    display: block;
    color: red;
}
.error-wallet-custom {
    border: 1px solid red;
}
</style>
