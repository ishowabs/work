<template>
  <div class="flex flex-col h-full w-full max-w-xl mx-auto bg-[#232323] rounded-lg p-6 border border-[#2a2a2a]">
    <h2 class="text-2xl font-bold mb-4">Chat</h2>
    <div class="flex-1 overflow-y-auto mb-4">
      <!-- Chat messages placeholder -->
      <div class="text-[#9ca3af]">Chat messages will appear here.</div>
    </div>
    <form class="flex gap-2">
      <input
        type="text"
        class="flex-1 rounded bg-[#1c1c1c] border border-[#2a2a2a] px-3 py-2 text-white focus:outline-none"
        placeholder="Type your message..."
        disabled
      />
      <button
        type="submit"
        class="bg-[#3ecf8e] text-black font-semibold px-4 py-2 rounded hover:bg-[#2dd4bf] transition-colors"
        disabled
      >
        Send
      </button>
    </form>
  </div>
</template>