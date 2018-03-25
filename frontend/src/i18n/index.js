import Vue from 'vue'
import VueI18n from 'vue-i18n'
import en from './lang/en'
import ru from './lang/ru'

Vue.use(VueI18n)

const messages = {
  en,
  ru
}
function getLocale () {
  let lang = (navigator.language || navigator.userLanguage).substring(0, 2)
  if (lang !== 'ru' && lang !== 'en') {
    return 'en'
  }
  return lang
}
const i18n = new VueI18n({
  locale: getLocale(),
  messages
})

export default i18n
