<template>
    <div class="dashboard-content">
        <div class="card col-md-12 col-xs-12">
            <div class="header">
                <h4 class="title">My Actions</h4>
            </div>
            <div v-if="user.actions" class="content">
                <div class="row">
                    <ul class="list-group list-group-flush">
                        <li v-for="(action,index) in user.actions" :key="index" class="list-group-item">
                            {{action.message}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        title="My Transactions"
                        :columns="transactions.columns"
                        :rows="transactions.data"
                        :perPage="5"
                        :lineNumbers="true"/>
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        title="My Withdrawals"
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
                                decline
                            </button>
                        </td>
                    </template>
                </vue-good-table>
            </div>
        </div>

        <div class="card col-md-12 col-xs-12">
            <div class="header">
                <h3 class="title" style="margin-bottom: 30px">My Masternodes</h3>
            </div>
            <div v-for="(node, index) in nodes" :key="index" class="col-md-4 col-xs-4">
                <chart-card :chart-data="node.data" chart-type="Pie">
                    <h4 class="title" slot="title">{{node.currency.name}}</h4>
                    <span slot="subTitle">State: {{node.state}}</span>
                    <span slot="footer">
                        <button type="button" class="btn btn-danger" :id="node.id" @click="leaveNode">
                            Leave from node
                        </button>
                    </span>
                    <div slot="legend">
                        <i class="fa fa-circle text-info"></i> Other share
                        <i v-if="node.showFree" class="fa fa-circle text-danger"></i> Free share
                        <i class="fa fa-circle text-warning"></i> Your share
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

export default{
  components: {
    ChartCard
  },
  beforeCreate () {
    request.getUserActions().then(res => {
      this.user.actions = response.getResponse(res)
    }).catch(err => {
      response.handleErrors(err, this)
    })
    request.getUserNodes().then(res => {
      const nodes = response.getResponse(res)
      for (let index in nodes) {
        let price = nodes[index].price
        let investments = nodes[index].bill.amount
        let investor = nodes[index].investor.amount
        let other = ((investments - investor) * 100) / price
        let userShare = (investor * 100) / price
        let free = ((price - investments) * 100) / price
        nodes[index].data = {
          labels: free === 0 ? [other + '%', userShare + '%'] : [other + '%', userShare + '%', free + '%'],
          series: free === 0 ? [other, userShare] : [other, userShare, free]
        }
        nodes[index].showFree = !!free
      }
      this.nodes = nodes
    }).catch(err => {
      response.handleErrors(err, this)
    })
    request.getUserTransactions().then(res => {
      let transactions = response.getResponse(res)
      this.transactions.columns = [
        {
          label: 'Currency',
          field: 'currency'
        },
        {
          label: 'Amount',
          field: 'amount',
          type: 'number'
        },
        {
          label: 'Date',
          field: 'date',
          type: 'date',
          inputFormat: 'YYYY-MM-DD',
          outputFormat: 'MMM Do YYYY'
        },
        {
          label: 'Type',
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
    request.getUserWithdrawals().then(res => {
      let withdrawals = response.getResponse(res)
      this.withdrawals.columns = [
        {
          label: 'Currency',
          field: 'currency'
        },
        {
          label: 'Amount',
          field: 'amount',
          type: 'number'
        },
        {
          label: 'Date',
          field: 'date'
        },
        {
          label: 'State',
          field: 'state'
        },
        {
          label: 'Decline',
          sortable: false
        }
      ]
      for (let index in withdrawals) {
        this.withdrawals.data.push({
          currency: withdrawals[index].currency,
          amount: withdrawals[index].amount,
          //date: withdrawals[index].created.date,
          state: withdrawals[index].state
        })
      }
    }).catch(err => {
      console.log(err)
    })
  },
  methods: {
    leaveNode (e) {
      let creds = {}
      creds.node_id = e.target.id
      request.withdrawalNode(creds).then(res => {
        response.handleSuccess(res, this)
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    declineWithdrawal (id) {
      request.declineWithdrawal(id).then(res => {
        console.log(res)
      }).catch(err => {
        console.log(err.response)
      })
    }
  },
  data () {
    return {
      user: {
        actions: false
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
