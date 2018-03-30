<template>
    <div class="admin-nodes">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        title="Мастерноды"
                        :columns="nodes.columns"
                        :rows="nodes.data"
                        :perPage="15"
                        :paginate="true"
                        :globalSearch="true"
                        :lineNumbers="true">
                    <template slot="table-row-after" slot-scope="props">
                        <td style="width: 20%">
                            <!--<button @click="fillInModal(props.row)" type="button" class="btn btn-success pull-left">
                                Распредилить прибыль
                            </button>-->
                            <node-profit :node="props.row"></node-profit>
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
import NodeProfit from './Modals/NodeProfit.vue'

export default {
  components: {
    Spinner,
    WindowModal,
    NodeProfit
  },
  data () {
    return {
      nodes: {
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
    request.getAdminNodes(this.$i18n.locale).then(res => {
      let resNode = response.getResponse(res)
      this.nodes.columns = [
        {
          label: 'Идентификатор',
          field: 'id',
          type: 'number'
        },
        {
          label: 'Валюта',
          field: 'currency'
        },
        {
          label: 'Цена ноды',
          field: 'price',
          type: 'number'
        },
        {
          label: 'Состояние',
          field: 'state'
        },
        {
          label: 'Тип',
          field: 'type'
        },
        {
          label: 'Счет ноды',
          field: 'amount',
          type: 'number'
        },
        {
          label: this.$t('bills.columns.actions'),
          sortable: false
        }
      ]
      for (let index in resNode) {
        this.nodes.data.push({
          currency: resNode[index].currency.name,
          price: resNode[index].price,
          amount: resNode[index].bill.amount,
          id: resNode[index].id,
          state: resNode[index].state,
          type: resNode[index].type
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {}
}
</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
</style>
