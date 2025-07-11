<template>
  <div class="sidebar">
    <div class="sidebar-title">{{ $t('sidebar.users') }}</div>
    <div v-for="user in users" :key="user.id" @click="$emit('select', user)"
      :class="{ active: selectedUser && user.id === selectedUser.id }">
      {{ user.name }}
    </div>
  </div>
</template>
<script setup lang="ts">
import { useI18n } from 'vue-i18n'
useI18n()
defineProps(['users', 'selectedUser'])
</script>