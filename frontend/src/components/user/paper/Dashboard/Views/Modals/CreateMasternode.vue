<template>
    <div class="create-masternode">
        <slot name="open-modal">
            <p>
                <a class="btn btn-primary btn-lg" href="#" role="button" @click="getCurrency">
                    {{$t("masternode.create.buttons.create")}}
                </a>
            </p>
        </slot>
        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">{{$t("masternode.create.title")}}</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <select v-model="currency" class="form-control form-control-lg border-input">
                            <option disabled value="">{{$t("masternode.create.currency")}}</option>
                            <option v-for="(currency,index) in currencies"
                                    :key="index"
                                    :value="currency">
                                {{currency.name}}
                            </option>
                        </select>
                        <div v-if="currency" class="type-node-container">
                            <h4 class="title">{{$t("masternode.create.typeTitle")}}</h4>
                            <select v-model="nodeType" class="form-control form-control-lg border-input">
                                <option disabled value="">{{$t("masternode.create.type")}}</option>
                                <option value="single">
                                    single
                                </option>
                                <option value="party">
                                    party
                                </option>
                            </select>
                        </div>
                        <div v-if="nodeType === 'party'" class="share-content-container">
                            <h4 class="title">{{$t("masternode.create.title")}}</h4>
                            <h5>{{$t("masternode.create.shareCost")}}: {{currency.share.share_price}}</h5>
                            <input class="form-control border-input" type="range"
                                   min="1" :max="currency.share.full_price / currency.share.share_price" step="1"
                                   v-model="sharesCount">
                            <input class="form-control border-input" type="number"
                                   min="1" :max="currency.share.full_price / currency.share.share_price" step="1"
                                   v-model="sharesCount"
                                   :class="{'error-create-node':!isValidShare}">
                            <span class="error-create-node-validate"
                                  v-if="!isValidShare">{{$t("validate.shares") + currency.share.full_price / currency.share.share_price}}</span>
                        </div>
                        <div v-if="currency" class="type-node-container pull-left">
                            <h5>{{$t("masternode.create.fullCost")}}: {{currency.share.full_price}}</h5>
                        </div>
                        <div v-if="currency" class="type-node-container pull-right">
                            <h5 style="color: #8FBC8F;">
                                {{$t("masternode.create.toPay")}}: {{toPay(currency.share.full_price, currency.share.share_price)}}</h5>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button :disabled="!isValidShare" class="two-fa-button btn btn-success btn-fill btn-wd"
                                @click.prevent="create({currency,nodeType, sharesCount})">
                            {{$t("masternode.create.buttons.create")}}
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

  methods: {
    openModal () {
      this.showModal = true
      this.isValidEmail = true
      this.isValidPassword = true
    },
    getCurrency () {
      this.openModal()
      this.snipper = true
      request.getCurrencies(this.$i18n.locale).then(res => {
        this.currencies = response.getResponse(res)
        this.snipper = false
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    create (data) {
      let dataToSend = {}
      dataToSend.currency_id = data.currency.id
      dataToSend.type = data.nodeType
      dataToSend.count = data.sharesCount
      let freeShares = data.currency.share.full_price / data.currency.share.share_price
      this.isValidShare = validator.sharesCount(this.sharesCount, freeShares)
      if (this.isValidShare) {
        request.createNode(dataToSend, this.$i18n.locale).then(res => {
          response.handleSuccess(res, this)
          this.$emit('refreshNodes')
        }).catch(err => {
          response.handleErrors(err, this)
        })
      }
    },
    toPay (fullPrice, sharePrice) {
      if (this.nodeType === 'single') {
        this.sharesCount = fullPrice / sharePrice
        return fullPrice
      }
      if (this.nodeType === 'party') {
        let toPay = sharePrice * this.sharesCount
        if (toPay > fullPrice) {
          return fullPrice
        }
        return toPay
      }
    }
  },
  computed: {},
  watch: {
    sharesCount () {
      let freeShares = this.currency.share.full_price / this.currency.share.share_price
      this.isValidShare = validator.sharesCount(this.sharesCount, freeShares)
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
      isValidShare: true
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
