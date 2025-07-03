<template>
  <v-container>
    <v-card>
      <v-card-title>Live Chat</v-card-title>
      <v-card-text>
        <div style="height:300px; overflow-y:auto;" ref="messagesContainer">
          <div v-for="(msg, idx) in messages" :key="idx" class="mb-2">
            <strong>{{ msg.user || 'User' }}:</strong> {{ msg.text }}
          </div>
        </div>
        <v-form @submit.prevent="sendMessage" class="d-flex mt-4">
          <v-text-field
            v-model="message"
            label="Type your message"
            hide-details
            class="flex-grow-1"
            @keyup.enter="sendMessage"
          />
          <v-btn type="submit" color="primary" :disabled="!message">Send</v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import { io } from 'socket.io-client'

const socket = io('http://localhost:3001')
const messages = ref<{ user?: string; text: string }[]>([])
const message = ref('')
const messagesContainer = ref<HTMLElement | null>(null)

const sendMessage = () => {
  if (!message.value) return
  socket.emit('chat message', { text: message.value })
  message.value = ''
}

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

onMounted(() => {
  socket.on('chat message', (msg) => {
    messages.value.push(msg)
    scrollToBottom()
  })
})

onUnmounted(() => {
  socket.off('chat message')
})
</script>