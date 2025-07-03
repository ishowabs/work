FROM node:18-alpine

WORKDIR /app
COPY chat-server.js package.json package-lock.json ./
RUN npm install

EXPOSE 3001
CMD ["node", "chat-server.js"]
