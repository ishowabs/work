import { createI18n } from 'vue-i18n'
import en from '~/locales/en.json'
import fr from '~/locales/fr.json'

export default defineNuxtPlugin(({ vueApp }) => {
  const savedLang = process.client ? localStorage.getItem('lang') : null
  const i18n = createI18n({
    legacy: false,
    locale: savedLang || 'en',
    fallbackLocale: 'en',
    messages: { en, fr }
  })
  vueApp.use(i18n)
})
