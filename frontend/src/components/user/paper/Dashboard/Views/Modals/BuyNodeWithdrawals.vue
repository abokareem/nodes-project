<template>
    <div class="buy-node-withdrawals-container">
        <slot name="open-modal">
            <button class="btn btn-success" @click="openModal">
                {{$t("withdrawals.buttons.buy")}}
            </button>
        </slot>
        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">{{$t("withdrawals.title")}}</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <vue-good-table
                                style="color: #252422"
                                :title="$t('withdrawals.subTitle')"
                                :columns="withdrawals.columns"
                                :rows="withdrawals.data"
                                :perPage="5"
                                :paginate="true"
                                :globalSearch="true"
                                :lineNumbers="true">
                            <template slot="table-row-after" slot-scope="props">
                                <td>
                                    <button v-if="props.row.state === $store.state.withdrawals.processing"
                                            @click="buy(props.row.id)" type="button"
                                            class="btn btn-success">
                                        {{$t("withdrawals.buttons.buy")}}
                                    </button>
                                </td>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-success btn-fill btn-wd"
                                @click="showModal = false">
                            {{$t("withdrawals.buttons.close")}}
                        </button>
                    </div>
                </div>
            </window-modal>
        </div>
    </div>
</template>
<script>
import WindowModal from '../../../../../modal/Modal'
import request from '../../../../../../services/axios'
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import response from '../../../../../../services/response'

export default {

  components: {
    Spinner,
    WindowModal
  },
  props: {
    node: {
      type: Object
    }
  },
  data () {
    return {
      snipper: false,
      showModal: false,
      withdrawals: {
        columns: [],
        data: []
      }
    }
  },
  created () {
    request.getNodesWithdrawals(this.node.id, this.$i18n.locale).then(res => {
      let withdrawals = response.getResponse(res)
      this.withdrawals.columns = [
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
          label: this.$t('dashboard.columns.date'),
          field: 'date',
          type: 'date',
          inputFormat: 'YYYY-MM-DD',
          outputFormat: 'YYYY-MM-DD'
        },
        {
          label: this.$t('dashboard.columns.state'),
          field: 'state'
        },
        {
          label: this.$t('dashboard.withdrawal.decline'),
          sortable: false
        }
      ]
      for (let index in withdrawals) {
        this.withdrawals.data.push({
          currency: withdrawals[index].currency ? withdrawals[index].currency.name : null,
          amount: withdrawals[index].amount,
          id: withdrawals[index].id,
          date: withdrawals[index].created.date,
          state: withdrawals[index].state
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {
    openModal () {
      this.showModal = true
    },
    buy (id) {
      request.buyNodesWithdrawal(id, this.$i18n.locale).then(res => {
        response.handleSuccess(res, this)
        for (let index in this.withdrawals) {
          if (this.withdrawals[index].id === id) {
            delete this.withdrawals[index]
          }
        }
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
