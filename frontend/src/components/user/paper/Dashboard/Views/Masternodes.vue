<template>
    <div class="masternodes">
        <div class="jumbotron">
            <h1>Create your own Masternode</h1>
            <p>or unite with others</p>
            <create-masternode></create-masternode>
        </div>
        <div class="card col-md-12 col-xs-12">
            <div class="header">
                <h3 class="title" style="margin-bottom: 30px">Masternodes</h3>
            </div>
            <div v-for="(node, index) in nodes" :key="index" class="col-md-12 col-xs-12">
                <chart-card :chart-data="node.data" chart-type="Pie">
                    <h4 class="title" slot="title">{{node.currency.name}}</h4>
                    <span slot="subTitle"><br>Share price: {{node.sharePrice}} <br><br> Free Shares: {{node.freeShares}}</span>
                    <span slot="footer">
                        <!--<button type="button" class="btn btn-success" @click="buyShares(node.id)">
                            Buy shares
                        </button>-->
                    </span>
                    <div slot="legend">
                        <p v-if="node.showOther" style="display: inline-block">
                            <i class="fa fa-circle text-info"></i>
                            Other share
                        </p>
                        <p v-if="node.showFree" style="display: inline-block">
                            <i class="fa fa-circle text-danger"></i>
                            Free share
                        </p>
                        <p v-if="node.showUser" style="display: inline-block">
                            <i class="fa fa-circle text-warning"></i>
                            Your share
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
import CreateMasternode from './Modals/CreateMasternode.vue'

export default{
  components: {
    ChartCard,
    CreateMasternode
  },
  beforeCreate () {
    request.getNodes().then(res => {
      const nodes = response.getResponse(res)
      for (let index in nodes) {
        let price = nodes[index].price
        let investments = nodes[index].bill.amount
        let investor = nodes[index].investor ? nodes[index].investor.amount : null
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
        nodes[index].sharePrice = nodes[index].currency.share.share_price
        nodes[index].freeShares = price - investments / nodes[index].currency.share.share_price
        nodes[index].data = {
          labels: labels,
          series: series
        }
      }
      this.nodes = nodes
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {
    buyShares (id) {

    }
  },
  data () {
    return {
      nodes: ''
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../assets/sass/bootstrap.css";
@import "../../../../../assets/sass/paper-dashboard.scss";
</style>
