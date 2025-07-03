<template>
  <v-app>
    <v-navigation-drawer app color="#1c1c1c" dark>
      <v-list>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title class="text-h6" style="color:#3ecf8e;">Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider></v-divider>
        <v-list-item
          :active="activeTab === 'clients'"
          @click="activeTab = 'clients'"
          class="rounded-lg my-1"
        >
          <v-list-item-title>Users List</v-list-item-title>
        </v-list-item>
        <v-list-item
          :active="activeTab === 'zone'"
          @click="activeTab = 'zone'"
          class="rounded-lg my-1"
        >
          <v-list-item-title>Zone</v-list-item-title>
        </v-list-item>
        <v-list-item
          :active="activeTab === 'chart'"
          @click="activeTab = 'chart'"
          class="rounded-lg my-1"
        >
          <v-list-item-title>Chart</v-list-item-title>
        </v-list-item>
       
        
        <v-list-item
          :active="activeTab === 'settings'"
          @click="activeTab = 'settings'"
          class="rounded-lg my-1"
        >
          <v-list-item-title>Settings</v-list-item-title>
        </v-list-item>
      </v-list>
      <template #append>
        <v-btn
          color="#fca5a5"
          class="mt-8"
          block
          @click="logout"
        >Logout</v-btn>
      </template>
    </v-navigation-drawer>
    <v-main class="bg-[#0c0c0c] text-white">
      <v-container>
        <ChartComponent v-if="activeTab === 'chart'" />
        <ChatComponent v-if="activeTab === 'chat'" />
        <div v-if="activeTab === 'settings'" class="text-white">Settings page coming soon.</div>
        <UsersList v-if="activeTab === 'clients'" />
        <Zone v-if="activeTab === 'zone'" /> <!-- Add this line for chat zone -->
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import ChartComponent from '../components/ChartComponent.vue'
import ChatComponent from '../components/ChatComponent.vue'
import UsersList from '../components/UsersList.vue'
import Zone from '../components/Zone.vue' // Import your Zone component

const router = useRouter()
const activeTab = ref('clients')

onMounted(() => {
  const token = localStorage.getItem('jwt')
  if (!token) {
    router.replace('/login')
  }
})

function logout() {
  localStorage.removeItem('jwt')
  router.replace('/login')
}
</script> 