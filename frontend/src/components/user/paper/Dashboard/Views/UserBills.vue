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
                                    @click="fillInModal(props.row)" type="button" class="btn btn-success pull-left">
                                {{$t("bills.buttons.fill")}}
                            </button>
                            <button v-if="props.row.isBill"
                                    @click="withdrawalMoneyModal(props.row)" type="button"
                                    class="btn btn-danger pull-right">
                                {{$t("bills.buttons.withdrawal")}}
                            </button>
                            <button v-if="!props.row.isBill"
                                    @click="createBill(props.row)" type="button" class="btn btn-success">
                                {{$t("bills.buttons.create")}}
                            </button>
                        </td>
                    </template>
                </vue-good-table>
            </div>
        </div>
        <div @click="showBillModal = false">
            <window-modal v-if="showBillModal" @close="showBillModal = false">
                <h3 slot="header">{{$t("bills.modals.fill.title")}}</h3>
                <div slot="body">
                    <div class="row">
                        <h4>{{currentBill.wallet}}</h4>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-info btn-fill btn-wd"
                                @click="showBillModal = false">
                            {{$t("bills.buttons.fillTwo")}}
                        </button>
                    </div>
                </div>
            </window-modal>
        </div>
        <div @click="showWithdrawalModal = false">
            <window-modal v-if="showWithdrawalModal" @close="showWithdrawalModal = false">
                <h3 slot="header">{{$t("bills.modals.withdrawal.title")}}</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="spinner" :size="60"></spinner>
                        <div class="text-center">
                            <label for="wallet-input">
                                {{$t("bills.modals.withdrawal.wallet")}}
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
                                {{$t("bills.modals.withdrawal.amount")}}
                            </label>
                            <input class="form-control border-input" type="number"
                                   max="freePrice"
                                   id="withdrawal-input"
                                   v-model="price"
                                   :class="{'error-create-node':!isValidPrice}">
                            <span class="error-create-node-validate"
                                  v-if="!isValidPrice">{{$t("validate.price")}}</span>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-danger btn-fill btn-wd"
                                @click="withdrawalMoney({wallet, price})">
                            {{$t("bills.buttons.withdrawal")}}
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
import validator from '../../../../../services/validator'

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
      showBillModal: false,
      isValidWallet: true,
      isValidPrice: true,
      price: 0,
      wallet: '',
      currentBill: '',
      spinner: false
    }
  },
  created () {
    request.getUser(this.$i18n.locale).then(res => {
      let resBills = response.getResponse(res).bills
      let existBill = []
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
          label: this.$t('bills.columns.actions'),
          sortable: false
        }
      ]
      for (let index in resBills) {
        this.bills.data.push({
          currency: resBills[index].currency.name,
          amount: resBills[index].amount,
          id: resBills[index].id,
          wallet: resBills[index].bill,
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
              currency_id: currencies[index].id,
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
    withdrawalMoneyModal (bill) {
      this.showWithdrawalModal = true
      this.currentBill = bill
    },
    withdrawalMoney (data) {
      data.bill_id = this.currentBill.id
      request.withdrawalMoney(data, this.$i18n.locale).then(res => {
        let updatedBill = response.getResponse(res)
        for (let index in this.bills.data) {
          if (updatedBill.id === this.bills.data[index].id) {
            this.bills.data[index].amount = updatedBill.amount
          }
        }
        this.$notifications.notify({
          message: '<h3>' + this.$t('bills.message.withdrawal') + '</h3>',
          icon: 'ti-bell',
          horizontalAlign: 'right',
          verticalAlign: 'bottom',
          type: 'success',
          timeout: 2000
        })
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    fillInModal (bill) {
      this.showBillModal = true
      this.currentBill = bill
    },
    createBill (currency) {
      request.createBill(currency, this.$i18n.locale).then(res => {
        let newBill = response.getResponse(res)
        for (let index in this.bills.data) {
          if (currency.currency_id === this.bills.data[index].currency_id) {
            this.bills.data[index].amount = newBill.amount
            this.bills.data[index].id = newBill.id
            this.bills.data[index].wallet = newBill.bill
            this.bills.data[index].isBill = true
          }
        }
        this.$notifications.notify({
          message: '<h3>' + this.$t('bills.messages.create') + '</h3>',
          icon: 'ti-bell',
          horizontalAlign: 'right',
          verticalAlign: 'bottom',
          type: 'success',
          timeout: 2000
        })
      }).catch(err => {
        response.handleErrors(err, this)
      })
    }
  },
  watch: {
    wallet () {
      this.isValidWallet = validator.wallet(this.wallet)
    },
    price () {
      this.isValidPrice = validator.withdrawalUserAmount(this.price, this.currentBill.amount)
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
.error-wallet-validate, .error-create-node-validate {
    display: block;
    color: red;
}
.error-wallet-custom, .error-create-node {
    border: 1px solid red !important;
}
</style>
