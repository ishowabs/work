<template>
  <div class="sidebar">
    <div v-for="user in users" :key="user.id" @click="$emit('select', user)"
      :class="{ active: selectedUser && user.id === selectedUser.id }">
      {{ user.name }}
    </div>
  </div>
</template>
<script setup lang="ts">
defineProps(['users', 'selectedUser'])
</script>