<template>
  <div class="flex flex-col items-center justify-center h-full w-full pt-10">
    <h2 class="text-2xl font-bold mb-6 text-white font-inter tracking-tight">Clients par Mois</h2>
    <div class="flex gap-4 mb-4">
      <button class="export-btn" @click="exportCSV">Export CSV</button>
      <button class="export-btn" @click="exportImage">Export Image</button>
    </div>
    <div class="bg-[#18181b] border border-[#262626] rounded-xl p-6 shadow supabase-card w-full max-w-2xl">
      <apexchart
        ref="apexRef"
        width="100%"
        height="320"
        type="bar"
        :options="chartOptions"
        :series="series"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const series = ref([
  {
    name: 'Nouveaux Clients',
    data: [12, 19, 14, 23, 17, 21, 15, 18, 24, 20, 16, 22] // Dummy data for each month
  }
])

const chartOptions = ref({
  chart: {
    type: 'bar',
    toolbar: { show: false },
    background: 'transparent',
    fontFamily: 'Inter, sans-serif'
  },
  plotOptions: {
    bar: {
      borderRadius: 6,
      columnWidth: '45%',
      distributed: false
    }
  },
  dataLabels: { enabled: false },
  xaxis: {
    categories: [
      'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin',
      'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'
    ],
    labels: {
      style: { colors: '#a1a1aa', fontFamily: 'Inter, sans-serif' }
    }
  },
  yaxis: {
    labels: {
      style: { colors: '#a1a1aa', fontFamily: 'Inter, sans-serif' }
    }
  },
  grid: {
    borderColor: '#262626',
    strokeDashArray: 4
  },
  colors: ['#3ecf8e'],
  tooltip: {
    theme: 'dark'
  }
})

const apexRef = ref()

function exportCSV() {
  const header = ['Month', ...series.value.map(s => s.name)]
  const months = chartOptions.value.xaxis.categories
  const rows = months.map((month, i) => [month, ...series.value.map(s => s.data[i])])
  const csv = [header, ...rows].map(r => r.join(',')).join('\n')
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'chart-data.csv'
  a.click()
  URL.revokeObjectURL(url)
}

function exportImage() {
  if (apexRef.value && apexRef.value.chart) {
    apexRef.value.chart.dataURI().then(({ imgURI }) => {
      const a = document.createElement('a')
      a.href = imgURI
      a.download = 'chart.png'
      a.click()
    })
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
.font-inter {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif !important;
}
.supabase-card {
  background: #18181b !important;
  border: 1px solid #262626 !important;
  border-radius: 12px !important;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.35) !important;
}
.export-btn {
  background: #232323;
  color: #3ecf8e;
  border: 1px solid #3ecf8e;
  border-radius: 6px;
  padding: 8px 18px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
}
.export-btn:hover {
  background: #3ecf8e;
  color: #18181b;
}
</style>