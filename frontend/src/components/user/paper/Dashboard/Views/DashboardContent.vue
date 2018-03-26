<template>
    <div class="dashboard-content">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        :title="$t('dashboard.actions.title')"
                        :columns="actions.columns"
                        :rows="actions.data"
                        :perPage="5"
                        :lineNumbers="true"/>
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        :title="$t('dashboard.transaction.title')"
                        :columns="transactions.columns"
                        :rows="transactions.data"
                        :perPage="5"
                        :lineNumbers="true"/>
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        :title="$t('dashboard.withdrawal.title')"
                        :columns="withdrawals.columns"
                        :rows="withdrawals.data"
                        :perPage="5"
                        :paginate="true"
                        :globalSearch="true"
                        :lineNumbers="true">
                    <template slot="table-row-after" slot-scope="props">
                        <td>
                            <button v-if="props.row.state === $store.state.withdrawals.processing"
                                    @click="declineWithdrawal(props.row.id)" type="button" class="btn btn-danger">
                                {{$t("dashboard.declineButton")}}
                            </button>
                        </td>
                    </template>
                </vue-good-table>
            </div>
        </div>

        <div class="card col-md-12 col-xs-12">
            <div class="header">
                <h3 class="title" style="margin-bottom: 30px">{{$t("dashboard.nodes.title")}}</h3>
            </div>
            <div v-for="(node, index) in nodes" :key="index" class="col-md-4 col-xs-4">
                <chart-card :chart-data="node.data" chart-type="Pie">
                    <h4 class="title" slot="title">{{node.currency.name}}</h4>
                    <span slot="subTitle">{{$t("dashboard.columns.state")}}: {{node.state}}</span>
                    <span slot="footer">
                        <button style="display: inline-block" type="button" class="btn btn-danger" :id="node.id" @click="leaveNode">
                            {{$t("dashboard.leaveButton")}}
                        </button>
                        <node-withdrawals v-if="node.state === $store.state.nodes.unstable"
                                          style="display: inline-block" :node="node"></node-withdrawals>
                    </span>
                    <div slot="legend">
                        <p v-if="node.showOther" style="display: inline-block">
                            <i class="fa fa-circle text-info"></i>
                            {{$t("dashboard.share.other")}}
                        </p>
                        <p v-if="node.showFree" style="display: inline-block">
                            <i class="fa fa-circle text-danger"></i>
                            {{$t("dashboard.share.free")}}
                        </p>
                        <p v-if="node.showUser" style="display: inline-block">
                            <i class="fa fa-circle text-warning"></i>
                            {{$t("dashboard.share.your")}}
                        </p>
                    </div>
                </chart-card>
            </div>
        </div>
    </div>
</template>
<script>
import request from '../../../../../services/axios'
import response from '../../../../../services/response'
import ChartCard from '../../UIComponents/Cards/ChartCard.vue'
import NodeWithdrawals from './Modals/BuyNodeWithdrawals.vue'

export default{
  components: {
    ChartCard,
    NodeWithdrawals
  },
  beforeCreate () {
    request.getUserActions(this.$i18n.locale).then(res => {
      let actions = response.getResponse(res)
      this.actions.columns = [
        {
          label: this.$t('dashboard.actions.message'),
          field: 'message'
        },
        {
          label: this.$t('dashboard.columns.date'),
          field: 'date',
          type: 'date',
          inputFormat: 'YYYY-MM-DD',
          outputFormat: 'YYYY-MM-DD'
        }
      ]
      for (let index in actions) {
        this.actions.data.push({
          message: actions[index].message,
          date: actions[index].created.date
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
    request.getUserNodes(this.$i18n.locale).then(res => {
      const nodes = response.getResponse(res)
      for (let index in nodes) {
        let price = nodes[index].price
        let investments = nodes[index].bill.amount
        let investor = nodes[index].investor.amount
        let other = ((investments - investor) * 100) / price
        let userShare = (investor * 100) / price
        let free = ((price - investments) * 100) / price
        let labels = [
          other ? other + '%' : '',
          userShare ? userShare + '%' : '',
          free ? free + '%' : ''
        ]
        let series = [other, userShare, free]
        if (!other) {
          delete series[0]
        }
        if (!userShare) {
          delete series[1]
        }
        if (!free) {
          delete series[2]
        }
        nodes[index].showOther = !!other
        nodes[index].showUser = !!userShare
        nodes[index].showFree = !!free
        nodes[index].data = {
          labels: labels,
          series: series
        }
      }
      this.nodes = nodes
    }).catch(err => {
      response.handleErrors(err, this)
    })
    request.getUserTransactions(this.$i18n.locale).then(res => {
      let transactions = response.getResponse(res)
      this.transactions.columns = [
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
          label: this.$t('dashboard.transaction.type'),
          field: 'type'
        }
      ]
      for (let index in transactions) {
        this.transactions.data.push({
          currency: transactions[index].currency.name,
          amount: transactions[index].amount,
          date: transactions[index].created.date,
          type: transactions[index].message
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
    request.getUserWithdrawals(this.$i18n.locale).then(res => {
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
    leaveNode (e) {
      let creds = {}
      creds.node_id = e.target.id
      request.withdrawalNode(creds, this.$i18n.locale).then(res => {
        response.handleSuccess(res, this)
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    declineWithdrawal (id) {
      request.declineWithdrawal(id, this.$i18n.locale).then(res => {
        response.handleSuccess(res, this)
      }).catch(err => {
        response.handleErrors(err, this)
      })
    }
  },
  data () {
    return {
      actions: {
        columns: [],
        data: []
      },
      transactions: {
        columns: [],
        data: []
      },
      withdrawals: {
        columns: [],
        data: []
      },
      nodes: ''
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../assets/sass/bootstrap.css";
@import "../../../../../assets/sass/paper-dashboard.scss";
</style>
