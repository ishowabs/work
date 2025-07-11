<template>
  <div class="userslist-container">
    <h1 class="userslist-title">{{ t('users_list.title') }}</h1>
    <div class="userslist-table-wrapper">
      <table class="userslist-table">
        <thead>
          <tr>
            <th>{{ t('users_list.name') }}</th>
            <th>{{ t('users_list.email') }}</th>
            <th>{{ t('users_list.created_at') }}</th>
            <th>{{ t('users_list.last_login_at') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ formatDate(user.created_at) }}</td>
            <td>{{ user.last_login_at ? formatDate(user.last_login_at) : t('users_list.never') }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
const users = ref<any[]>([])

onMounted(async () => {
  const token = localStorage.getItem('jwt')
  const res = await fetch('http://localhost:8000/api/users', {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json'
    }
  })
  if (res.ok) {
    users.value = await res.json()
  } else {
    users.value = []
  }
})

function formatDate(dateStr: string) {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleString()
}
</script>

<style scoped>
.userslist-container {
  background: #1c1c1c;
  border-radius: 12px;
  padding: 32px 24px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.4);
  max-width: 900px;
  margin: 40px auto;
}

.userslist-title {
  color: #fff;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 24px;
  letter-spacing: -0.02em;
  text-align: left;
}

.userslist-table-wrapper {
  overflow-x: auto;
}

.userslist-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  background: #232323;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(62, 207, 142, 0.08);
}

.userslist-table th,
.userslist-table td {
  padding: 14px 18px;
  text-align: left;
}

.userslist-table th {
  background: #262626;
  color: #3ecf8e;
  font-size: 15px;
  font-weight: 600;
  border-bottom: 2px solid #333;
}

.userslist-table td {
  color: #f3f4f6;
  font-size: 14px;
  border-bottom: 1px solid #2a2a2a;
}

.userslist-table tr:last-child td {
  border-bottom: none;
}

.userslist-table tr:hover td {
  background: #222c22;
  transition: background 0.2s;
}
</style>