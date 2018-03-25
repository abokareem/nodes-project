<template>
    <div class="buy-shares-container">
        <slot name="open-modal">
            <p>
                <a class="btn btn-success" href="#" role="button" @click="openModal">
                    {{$t("masternode.buy.buttons.buy")}}
                </a>
            </p>
        </slot>
        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">{{$t("masternode.buy.title")}}</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <div class="share-content-container">
                            <h4 class="title">{{$t("masternode.buy.subTitle")}}</h4>
                            <h5>{{$t("masternode.buy.shareCost")}}: {{node.currency.share.share_price}}</h5>
                            <input class="form-control border-input" type="range"
                                   min="1" :max="freeSharesCount" step="1"
                                   v-model="sharesCount">
                            <input class="form-control border-input" type="number"
                                   min="1" :max="freeSharesCount" step="1"
                                   v-model="sharesCount"
                                   :class="{'error-create-node':!isValidShare}">
                            <span class="error-create-node-validate"
                                  v-if="!isValidShare">{{$t("validate.shares") + freeSharesCount}}</span>
                        </div>
                        <div class="type-node-container pull-left">
                            <h5>{{$t("masternode.buy.free")}}: {{freeSharesCount}}</h5>
                        </div>
                        <div class="type-node-container pull-right">
                            <h5 style="color: #8FBC8F;">
                                {{$t("masternode.buy.toPay")}}: {{toPay()}}</h5>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button :disabled="!isValidShare" class="two-fa-button btn btn-success btn-fill btn-wd"
                                @click.prevent="buy(sharesCount)">
                            {{$t("masternode.buy.buttons.buy")}}
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
import validator from '../../../../../../services/validator'
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
      currencies: [],
      currency: '',
      nodeType: '',
      snipper: false,
      showModal: false,
      sharesCount: 0,
      freeShares: 0,
      isValidShare: true
    }
  },
  methods: {
    openModal () {
      this.showModal = true
      this.isValidEmail = true
      this.isValidPassword = true
    },
    buy (count) {
      let dataToSend = {}
      dataToSend.node_id = this.node.id
      dataToSend.count = count
      let freeShares = (this.node.price - this.node.bill.amount) / this.node.currency.share.share_price
      this.isValidShare = validator.sharesCount(this.sharesCount, freeShares)
      if (this.isValidShare) {
        request.buyShares(dataToSend, this.$i18n.locale).then(res => {
          response.handleSuccess(res, this)
        }).catch(err => {
          response.handleErrors(err, this)
        })
      }
    },
    toPay () {
      let toPay = this.node.currency.share.share_price * this.sharesCount
      if (toPay > this.node.price) {
        return this.node.price
      }
      return toPay
    }
  },
  computed: {
    freeSharesCount: function () {
      let freeCoins = this.node.price - this.node.bill.amount
      return freeCoins / this.node.currency.share.share_price
    }
  },
  watch: {
    sharesCount () {
      let freeShares = (this.node.price - this.node.bill.amount) / this.node.currency.share.share_price
      this.isValidShare = validator.sharesCount(this.sharesCount, freeShares)
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
input {
    margin-bottom: 10px;
}
.error-create-node-validate {
    color: red;
}
.error-create-node {
    border: 1px solid red;
}
.two-fa-button {
    margin-bottom: 25px;
    margin-top: 50px;
}
.activate-twofa-input {
    margin-top: 20px;
    display: inline-block;
    width: 80%;
}
</style>
