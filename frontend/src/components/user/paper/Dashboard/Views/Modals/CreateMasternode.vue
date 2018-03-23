<template>
    <div class="create-masternode">
        <slot name="open-modal">
            <p>
                <a class="btn btn-primary btn-lg" href="#" role="button" @click="getCurrency">
                    Create
                </a>
            </p>
        </slot>
        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">Create Masternode</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <select v-model="currency" class="form-control form-control-lg border-input">
                            <option disabled value="">Select the currency</option>
                            <option v-for="(currency,index) in currencies"
                                    :key="index"
                                    :value="currency">
                                {{currency.name}}
                            </option>
                        </select>
                        <div v-if="currency" class="share-content-container">
                            <h5>Cost one share: {{currency.share.share_price}}</h5>
                            <h5>Full cost Masternode: {{currency.share.full_price}}</h5>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-success btn-fill btn-wd"
                                @click.prevent="create({currency_id})">
                            Create
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
      request.getCurrencies().then(res => {
        this.currencies = response.getResponse(res)
        this.snipper = false
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    create (creds) {
      console.log(creds)
    }
  },
  watch: {
    currency () {
      console.log(this.currency.share)
    }
  },
  data () {
    return {
      currencies: [],
      currency: '',
      snipper: false,
      showModal: false
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
span {
    color: red;
}
.error {
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
