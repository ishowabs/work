<template>
  <div class="settings-container">
    <h2>{{ $t('settings.language') }}</h2>
    <div class="lang-toggle">
      <v-btn
        :color="selectedLang === 'fr' ? 'primary' : 'grey darken-2'"
        @click="changeLanguage('fr')"
        depressed
        class="lang-btn"
      >FR</v-btn>
      <v-btn
        :color="selectedLang === 'en' ? 'primary' : 'grey darken-2'"
        @click="changeLanguage('en')"
        depressed
        class="lang-btn"
      >EN</v-btn>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()
const selectedLang = ref(locale.value)

watch(locale, (val) => {
  selectedLang.value = val
})

function changeLanguage(lang: string) {
  locale.value = lang
  selectedLang.value = lang
  localStorage.setItem('lang', lang)
}
</script>

<style scoped>
.settings-container {
  max-width: 400px;
  margin: 40px auto;
  background: #232323;
  border-radius: 12px;
  padding: 32px 24px;
  color: #fff;
}
.lang-toggle {
  display: flex;
  justify-content: center;
  gap: 16px;
  margin-top: 24px;
}
.lang-btn {
  min-width: 80px;
  font-weight: bold;
  font-size: 16px;
  border-radius: 24px;
  transition: background 0.2s, color 0.2s;
}
</style>
