<template>
    <div class="admin-commissions">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        :title="$t('admin.commissions.title')"
                        :columns="commissions.columns"
                        :rows="commissions.data"
                        :perPage="15"
                        :paginate="true"
                        :globalSearch="true"
                        :lineNumbers="true">
                    <template slot="table-row-after" slot-scope="props">
                        <td style="width: 20%">
                            <button @click="changeModal(props.row)" type="button" class="btn btn-success pull-left">
                                {{$t("admin.commissions.buttons.change")}}
                            </button>
                        </td>
                    </template>
                </vue-good-table>
            </div>
        </div>
        <div @click="showCommissionModal = false">
            <window-modal v-if="showCommissionModal" @close="showCommissionModal = false">
                <h3 slot="header">{{$t('bills.columns.amount')}}</h3>
                <div slot="body">
                    <div class="row">
                        <input class="form-control border-input" type="number"
                               max="freePrice"
                               id="withdrawal-input"
                               v-model="amount">
                    </div>
                </div>
                <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="spinner" :size="60"></spinner>
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
import Spinner from 'vue-spinner-component/src/Spinner.vue'

export default {
  components: {
    Spinner,
    WindowModal
  },
  data () {
    return {
      commissions: {
        columns: [],
        data: []
      },
      showCommissionModal: false,
      currentBill: '',
      amount: 0,
      spinner: false
    }
  },
  created () {
    request.getCommissions(this.$i18n.locale).then(res => {
      console.log(res)
      let resCommisssions = response.getResponse(res)
      this.commissions.columns = [
        {
          label: this.$t('admin.commissions.columns.type'),
          field: 'type'
        },
        {
          label: this.$t('admin.commissions.columns.percent'),
          field: 'percent'
        },
        {
          label: this.$t('admin.columns.actions'),
          sortable: false
        }
      ]
      for (let index in resCommisssions) {
        this.commissions.data.push({
          percent: resCommisssions[index].percent,
          type: resCommisssions[index].type,
          id: resCommisssions[index].id
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {
    changeModal (bill) {
      this.showBillModal = true
      this.currentBill = bill
    },
    fillIn (amount) {
      amount.id = this.currentBill.id
      this.spinner = true
      request.fillInUserBill(amount, this.$i18n.locale).then(res => {
        let data = response.getResponse(res)
        this.currentBill.amount = data.amount
        response.handleSuccess(res, this)
        this.showBillModal = false
        this.spinner = false
      }).catch(err => {
        response.handleErrors(err, this)
        this.spinner = false
      })
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
</style>
