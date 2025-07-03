<template>
  <div class="zone-dm-layout">
    <aside class="zone-dm-sidebar">
      <div class="zone-dm-sidebar-header">Messages</div>
      <div class="zone-dm-users">
        <div
          v-for="user in filteredUsers"
          :key="user.id"
          :class="['zone-dm-user', { active: selectedUser && user.id === selectedUser.id }]"
          @click="selectUser(user)"
        >
          <span class="zone-dm-avatar">{{ user.name.charAt(0).toUpperCase() }}</span>
          <span class="zone-dm-username">{{ user.name }}</span>
        </div>
      </div>
    </aside>
    <main class="zone-dm-chat">
      <ChatWindow
        v-if="selectedUser"
        :user="selectedUser"
        :currentUser="currentUser"
      />
      <div v-else class="zone-dm-placeholder">
        <span>Select a user to start chatting</span>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import ChatWindow from './ChatWindow.vue'

const users = ref([])
const selectedUser = ref(null)
const currentUser = ref(null)

onMounted(async () => {
  const token = localStorage.getItem('jwt')
  const res = await fetch('http://localhost:8000/api/users', {
    headers: { Authorization: `Bearer ${token}` }
  })
  users.value = await res.json()
  const meRes = await fetch('http://localhost:8000/api/me', {
    headers: { Authorization: `Bearer ${token}` }
  })
  currentUser.value = await meRes.json()
})

const filteredUsers = computed(() =>
  users.value.filter(u => currentUser.value && u.id !== currentUser.value.id)
)

function selectUser(user) {
  selectedUser.value = user
}
</script>

<style scoped>
.zone-dm-layout {
  display: flex;
  height: 70vh;
  background: #18181b;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,0.4);
  min-width: 700px;
}

.zone-dm-sidebar {
  width: 260px;
  background: #232323;
  border-right: 1px solid #222;
  display: flex;
  flex-direction: column;
}

.zone-dm-sidebar-header {
  font-weight: 700;
  font-size: 20px;
  color: #fff;
  padding: 24px 20px 16px 20px;
  border-bottom: 1px solid #222;
}

.zone-dm-users {
  flex: 1;
  overflow-y: auto;
}

.zone-dm-user {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 20px;
  cursor: pointer;
  color: #f3f4f6;
  font-size: 16px;
  transition: background 0.15s;
}

.zone-dm-user:hover,
.zone-dm-user.active {
  background: #1c1c1c;
}

.zone-dm-avatar {
  background: #3ecf8e;
  color: #18181b;
  font-weight: 700;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
}

.zone-dm-username {
  font-weight: 500;
}

.zone-dm-chat {
  flex: 1;
  background: #18181b;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.zone-dm-placeholder {
  color: #9ca3af;
  font-size: 18px;
  text-align: center;
}
</style>