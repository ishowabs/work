<template>
  <div class="chat-window">
    <div class="chat-header">
      <span class="chat-avatar">{{ user.name.charAt(0).toUpperCase() }}</span>
      <span class="chat-username">{{ user.name }}</span>
    </div>
    <div class="chat-messages" ref="messagesContainer">
      <div
        v-for="(msg, idx) in messages"
        :key="idx"
        :class="['chat-bubble', msg.from_user_id === currentUser.id ? 'mine' : 'theirs']"
      >
        <span class="chat-bubble-content">{{ msg.content }}</span>
        <span class="chat-bubble-meta">{{ formatTime(msg.created_at) }}</span>
      </div>
    </div>
    <form class="chat-input-row" @submit.prevent="sendMessage">
      <input
        v-model="newMessage"
        class="chat-input"
        type="text"
        placeholder="Type a message..."
        autocomplete="off"
      />
      <button class="chat-send-btn" type="submit" :disabled="!newMessage.trim()">Send</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, nextTick } from 'vue'
import { io } from 'socket.io-client'

const props = defineProps(['user', 'currentUser'])
interface Message {
  from_user_id: number;
  to_user_id: number;
  content: string;
  created_at: string;
}

const messages = ref<Message[]>([])
const newMessage = ref('')
const messagesContainer = ref<HTMLElement | null>(null)
let socket: any = null

function scrollToBottom() {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

async function fetchMessages() {
  if (!props.user) return
  const token = localStorage.getItem('jwt')
  const res = await fetch(`http://localhost:8000/api/messages/${props.user.id}`, {
    headers: { Authorization: `Bearer ${token}` }
  })
  messages.value = await res.json()
  scrollToBottom()
}

onMounted(async () => {
  await fetchMessages()
  // Connect to socket.io
  socket = io('http://localhost:3001')
  socket.emit('join', props.currentUser.id)
  socket.on('private message', (msg: any) => {
    // Accept messages sent to or from the current chat user
    if (
      msg.from === props.user.id ||
      msg.to === props.user.id
    ) {
      messages.value.push({
        ...msg,
        from_user_id: msg.from,
        to_user_id: msg.to,
        content: msg.message,
        created_at: new Date().toISOString()
      })
      scrollToBottom()
    }
  })
})

watch(() => props.user, async () => {
  await fetchMessages()
})

watch(messages, scrollToBottom)

function sendMessage() {
  if (!newMessage.value.trim()) return
  // Save to backend
  fetch('http://localhost:8000/api/messages', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${localStorage.getItem('jwt')}`
    },
    body: JSON.stringify({ to_user_id: props.user.id, content: newMessage.value })
  })
  // Emit via socket
  if (socket) {
    socket.emit('private message', {
      to: props.user.id,
      from: props.currentUser.id,
      message: newMessage.value
    })
  }
  messages.value.push({
    from_user_id: props.currentUser.id,
    to_user_id: props.user.id,
    content: newMessage.value,
    created_at: new Date().toISOString()
  })
  newMessage.value = ''
  scrollToBottom()
}

function formatTime(dateStr: string) {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
}
</script>

<style scoped>
.chat-window {
  background: #232323;
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.4);
  display: flex;
  flex-direction: column;
  height: 100%;
  min-width: 350px;
  max-width: 600px;
  width: 100%;
  border: 1px solid #2a2a2a;
}

.chat-header {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 22px 28px 16px 28px;
  border-bottom: 1px solid #222;
  background: #232323;
}

.chat-avatar {
  background: linear-gradient(135deg, #3ecf8e 0%, #2dd4bf 100%);
  color: #18181b;
  font-weight: 700;
  border-radius: 50%;
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
}

.chat-username {
  color: #fff;
  font-weight: 600;
  font-size: 18px;
}

.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 24px 28px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  background: #232323;
}

.chat-bubble {
  max-width: 70%;
  padding: 10px 16px;
  border-radius: 18px;
  font-size: 15px;
  position: relative;
  word-break: break-word;
  display: flex;
  flex-direction: column;
  align-self: flex-start;
  background: #262626;
  color: #f3f4f6;
  box-shadow: 0 2px 8px rgba(62, 207, 142, 0.08);
}

.chat-bubble.mine {
  align-self: flex-end;
  background: linear-gradient(135deg, #3ecf8e 0%, #2dd4bf 100%);
  color: #18181b;
}

.chat-bubble-content {
  margin-bottom: 4px;
}

.chat-bubble-meta {
  font-size: 11px;
  color: #9ca3af;
  align-self: flex-end;
}

.chat-input-row {
  display: flex;
  align-items: center;
  padding: 18px 24px 18px 24px;
  border-top: 1px solid #222;
  background: #232323;
  gap: 12px;
}

.chat-input {
  flex: 1;
  background: #18181b;
  border: 1px solid #404040;
  border-radius: 8px;
  color: #fff;
  font-size: 15px;
  padding: 12px 16px;
  outline: none;
  transition: border-color 0.2s;
}

.chat-input:focus {
  border-color: #3ecf8e;
  box-shadow: 0 0 0 3px rgba(62, 207, 142, 0.1);
}

.chat-send-btn {
  background: linear-gradient(135deg, #3ecf8e 0%, #2dd4bf 100%);
  color: #18181b;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 15px;
  padding: 10px 22px;
  cursor: pointer;
  transition: background 0.2s;
}

.chat-send-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.chat-send-btn:hover:not(:disabled) {
  background: linear-gradient(135deg, #2dd4bf 0%, #14b8a6 100%);
}
</style>