<template>
    <div class="admin-user-edit">
        <button class="btn btn-info" @click="openModal">
            Распредилить прибыль
        </button>

        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">Введите прибыль</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <div class="text-center">
                            <label for="admin-user-name">
                                Прибыль
                            </label>
                            <input v-model="profit"
                                   class="form-control border-input"
                                   type="number"
                                   id="admin-user-name"
                                   placeholder="Прибыль">
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-info btn-fill btn-wd"
                                @click="execute">
                            Распредилить
                        </button>
                    </div>
                </div>
            </window-modal>
        </div>
    </div>
</template>
<script>
import WindowModal from '../../../../../../modal/Modal'
import request from '../../../../../../../services/axios'
import Spinner from 'vue-spinner-component/src/Spinner.vue'
import validator from '../../../../../../../services/validator'
import response from '../../../../../../../services/response'

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
      profit: ''
    }
  },
  mounted () {

  },
  methods: {
    openModal () {
      this.showModal = true
    },
    execute () {
      let data = {
        id: this.node.id,
        amount: this.profit
      }
      this.spinner = true
      request.setProfit(data, this.$i18n.locale).then(res => {
        response.handleSuccess(res, this)
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
@import "../../../../../../../assets/sass/bootstrap.css";
@import "../../../../../../../assets/sass/paper-dashboard.scss";
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
