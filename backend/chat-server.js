import express from 'express'
import http from 'http'
import { Server } from 'socket.io'

const app = express()
const server = http.createServer(app)
const io = new Server(server, {
  cors: { origin: '*' }
})

io.on('connection', (socket) => {
  console.log('User connected:', socket.id)
  socket.on('chat message', (msg) => {
    io.emit('chat message', msg)
  })
  socket.on('disconnect', () => {
    console.log('User disconnected:', socket.id)
  })
  socket.on('join', userId => {
    socket.join(`user_${userId}`);
  });
  socket.on('private message', ({ to, message, from }) => {
    io.to(`user_${to}`).emit('private message', { from, to, message });
  });
})

const PORT = 3001
server.listen(PORT, () => {
  console.log(`Chat server running on http://localhost:${PORT}`)
})