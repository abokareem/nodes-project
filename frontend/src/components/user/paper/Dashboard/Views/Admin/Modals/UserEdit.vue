<template>
    <div class="admin-user-edit">
        <button class="btn btn-info" @click="openModal">
            {{$t('admin.users.buttons.edit')}}
        </button>

        <div @click="showModal = false">
            <window-modal v-if="showModal" @close="showModal = false">
                <h3 slot="header">{{$t('admin.users.edit.title')}}</h3>
                <div slot="body">
                    <div class="row">
                        <spinner style="position: absolute;margin-left: auto;margin-right: auto;
        right: 0; left: 0;" v-if="snipper" :size="60"></spinner>
                        <div class="text-center">
                            <label for="admin-user-name">
                                Имя
                            </label>
                            <input class="form-control border-input"
                                   type="text"
                                   id="admin-user-name"
                                   placeholder="Имя">
                            <label for="admin-user-email">
                                Почта
                            </label>
                            <input class="form-control border-input"
                                   type="email"
                                   id="admin-user-email"
                                   placeholder="Почта">
                            <label for="admin-user-lang">
                                Язык
                            </label>
                            <select class="form-control form-control-lg border-input"
                                    id="admin-user-lang">
                                <option :value="user.lang">{{user.lang}}</option>
                                <option :value="secondLang">{{secondLang}}</option>
                            </select>
                            <label for="admin-user-role">
                                Роль
                            </label>
                            <select class="form-control form-control-lg border-input"
                                    id="admin-user-role">
                                <option value="1">{{firstRole}}</option>
                                <option value="2">{{secondRole}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <div class="text-center">
                        <button class="two-fa-button btn btn-info btn-fill btn-wd"
                                @click="updateUser">
                            Сохранить
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
    user: {
      type: Object
    }
  },
  data () {
    return {
      snipper: false,
      showModal: false,
      secondLang: '',
      firstRole: '',
      secondRole: ''
    }
  },
  mounted () {
    this.secondLang = this.user.lang === 'en' ? 'ru' : 'en'
    this.firstRole = this.user.group === 1 ? 'admin' : 'user'
    this.secondRole = this.user.group === 1 ? 'user' : 'admin'
  },
  methods: {
    openModal () {
      this.showModal = true
    },
    updateUser () {

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
