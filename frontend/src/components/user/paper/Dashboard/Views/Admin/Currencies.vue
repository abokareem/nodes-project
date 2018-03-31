<template>
    <div class="admin-currencies">
        <button @click="currencyModal(null)"
                type="button" class="create-currency btn btn-info btn-fill btn-lg pull-left">
            Создать
        </button>
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <vue-good-table
                        title="Валюты"
                        :columns="currencies.columns"
                        :rows="currencies.data"
                        :perPage="15"
                        :paginate="true"
                        :globalSearch="true"
                        :lineNumbers="true">
                    <template slot="table-row-after" slot-scope="props">
                        <td style="width: 20%">
                            <button @click="currencyModal(props.row)"
                                    type="button" class="btn btn-info pull-left">
                                Редактировать Валюту
                            </button>
                            <button @click="freeWalletsModal(props.row)"
                                    type="button" class="btn btn-info pull-left">
                                Загрузить кошельки
                            </button>
                        </td>
                    </template>
                </vue-good-table>
            </div>
        </div>
        <div @click="showCurrencyModal = false">
            <window-modal v-if="showCurrencyModal" @close="showCurrencyModal = false">
                <h3 v-if="!currentCurrency" slot="header">Создать Валюту</h3>
                <h3 v-if="currentCurrency" slot="header">Редактировать Валюту</h3>
                <div slot="body">
                    <div class="row">
                        <label for="admin-currency-name">
                            Название
                        </label>
                        <input v-model="name"
                               class="form-control border-input"
                               type="text"
                               id="admin-currency-name"
                               placeholder="Название">
                        <label for="admin-code-name">
                            Код
                        </label>
                        <input v-model="code"
                               class="form-control border-input"
                               type="text"
                               id="admin-code-name"
                               placeholder="Код">
                        <label for="admin-symbol-name">
                            Символ
                        </label>
                        <input v-model="symbol"
                               class="form-control border-input"
                               type="text"
                               id="admin-symbol-name"
                               placeholder="Символ">
                        <label for="admin-share-price-name">
                            Цена доли
                        </label>
                        <input v-model="share_price"
                               class="form-control border-input"
                               type="number"
                               id="admin-share-price-name"
                               placeholder="Цена доли">
                        <label for="admin-full-price-name">
                            Полная цена
                        </label>
                        <input v-model="full_price"
                               class="form-control border-input"
                               type="number"
                               id="admin-full-price-name"
                               placeholder="Полная доли">
                    </div>
                </div>
                <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="spinner" :size="60"></spinner>
                <div slot="footer">
                    <div class="text-center">
                        <button v-if="!currentCurrency"
                                class="two-fa-button btn btn-info btn-fill btn-wd"
                                @click="createCurrency({name,code,symbol,share_price,full_price})">
                            Создать
                        </button>
                        <button v-if="currentCurrency"
                                class="two-fa-button btn btn-info btn-fill btn-wd"
                                @click="editCurrency({name,code,symbol,share_price,full_price})">
                            Редактировать
                        </button>
                    </div>
                </div>
            </window-modal>
        </div>
        <div @click="showFreeWalletsModal = false">
            <window-modal v-if="showFreeWalletsModal" @close="showFreeWalletsModal = false">
                <h3 slot="header">Загрузить кошельки</h3>
                <div slot="body">
                    <div class="row">
                        <label for="admin-free-wallets">
                            Введите кошельки для данной валюты, каждый с новой строки
                        </label>
                        <textarea v-model="freeWallets"
                                  class="form-control border-input"
                                  type="text"
                                  id="admin-free-wallets"
                                  placeholder="Введите кошельки, каждый с новой строки"></textarea>
                    </div>
                </div>
                <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="spinner" :size="60"></spinner>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-info btn-fill btn-wd"
                                @click="loadWallets">
                            Загрузить
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
      currencies: {
        columns: [],
        data: []
      },
      showCurrencyModal: false,
      showFreeWalletsModal: false,
      currentCurrency: '',
      name: '',
      code: '',
      symbol: '',
      share_price: '',
      full_price: '',
      freeWallets: [],
      spinner: false
    }
  },
  created () {
    request.getAdminCurrencies(this.$i18n.locale).then(res => {
      let resCurrency = response.getResponse(res)
      this.currencies.columns = [
        {
          label: 'Валюта',
          field: 'currency'
        },
        {
          label: 'Код',
          field: 'code'
        },
        {
          label: 'Символ',
          field: 'symbol'
        },
        {
          label: 'Цена доли',
          field: 'sharePrice'
        },
        {
          label: 'Цена полной ноды',
          field: 'fullPrice'
        },
        {
          label: 'Действие',
          sortable: false
        }
      ]
      for (let index in resCurrency) {
        this.currencies.data.push({
          currency: resCurrency[index].name,
          sharePrice: resCurrency[index].share.share_price,
          code: resCurrency[index].code,
          symbol: resCurrency[index].symbol,
          fullPrice: resCurrency[index].share.full_price,
          shareId: resCurrency[index].share.id,
          currencyId: resCurrency[index].id
        })
      }
    }).catch(err => {
      response.handleErrors(err, this)
    })
  },
  methods: {
    currencyModal (data) {
      this.showCurrencyModal = true
      this.currentCurrency = data
      if (data) {
        this.name = data.currency
        this.code = data.code
        this.symbol = data.symbol
        this.share_price = data.sharePrice
        this.full_price = data.fullPrice
      } else {
        this.name = ''
        this.code = ''
        this.symbol = ''
        this.share_price = ''
        this.full_price = ''
      }
    },
    freeWalletsModal (data) {
      this.showFreeWalletsModal = true
      this.currentCurrency = data
    },
    loadWallets () {
      let data = {
        currency_id: this.currentCurrency.currencyId,
        wallets: this.freeWallets
      }
      request.loadFreeWallets(data, this.$i18n.locale).then(res => {
        response.handleSuccess(res, this)
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    createCurrency (data) {
      request.createCurrency(data, this.$i18n.locale).then(res => {
        let newCurrency = response.getResponse(res)
        data.id = newCurrency.id
        request.createShare(data, this.$i18n.locale).then(resp => {
          let newResource = response.getResponse(resp)
          this.currencies.data.push({
            currency: newResource.name,
            sharePrice: newResource.share.share_price,
            code: newResource.code,
            symbol: newResource.symbol,
            fullPrice: newResource.share.full_price,
            shareId: newResource.share.id,
            currencyId: newResource.id
          })
          response.handleSuccess(newResource, this)
        }).catch(err => {
          response.handleErrors(err, this)
        })
      }).catch(err => {
        response.handleErrors(err, this)
      })
    },
    editCurrency (data) {
      data.currencyId = this.currentCurrency.currencyId
      data.shareId = this.currentCurrency.shareId
      request.editCurrency(data, this.$i18n.locale).then(res => {
        request.editShare(data, this.$i18n.locale).then(resShare => {
          response.handleSuccess(resShare, this)
          this.currentCurrency.currency = this.name
          this.currentCurrency.code = this.code
          this.currentCurrency.symbol = this.symbol
          this.currentCurrency.sharePrice = this.share_price
          this.currentCurrency.fullPrice = this.full_price
        }).catch(errShare => {
          response.handleErrors(errShare, this)
        })
      })
    }
  }
}
</script>
<style lang="scss" scoped>
@import "../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../assets/sass/paper-dashboard.scss";
.create-currency {
    margin: 40px 0;
}
</style>
