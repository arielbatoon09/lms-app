<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, onMounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
  chartData: {
    type: Object,
    required: true,
  },
  totalUser: {
    type: Number,
    required: true,
  },
  totalActiveBooks: {
    type: Number,
    required: true,
  },
  bookRequestCount: {
    type: Number,
    required: true,
  },
  sumTotalIncome: {
    type: Number,
    required: true,
  }
});

const loaded = ref(false);
const chartData = ref(null);

const chartOptions = ref({
  responsive: true,
  plugins: {
    title: {
      display: true,
      text: 'Monthly Sales Analytics',
    },
  },
});

onMounted(() => {
  loaded.value = false;

  try {
    chartData.value = {
      labels: Object.keys(props.chartData),
      datasets: [{ 
        data: Object.values(props.chartData),
        backgroundColor: [
          'rgba(54, 162, 235, 0.8)',
        ],
       }],
    };

    loaded.value = true;
  } catch (e) {
    console.error(e);
  }
});


</script>

<template>
  <Head title="Admin Dashboard" />
  <AdminLayout>
    <!-- Body-Content -->
    <div class="w-full h-full px-5 lg:px-14 py-10">
      <div class="h-screen">
        <!-- Total and Status -->
        <h2 class="text-gray-600 font-bold text-xl mb-2">LMS Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
          <div class="bg-white px-4 py-5 border-l-4 border-blue-400 shadow">
            <h2 class="font-bold text-gray-700 text-xl mb-3">Total User</h2>
            <p class="text-lg text-gray-500">{{ props.totalUser }}</p>
          </div>
          <div class="bg-white px-4 py-5 border-l-4 border-green-400 shadow">
            <h2 class="font-bold text-gray-700 text-xl mb-3">Total Available Books</h2>
            <p class="text-lg text-gray-500">{{ props.totalActiveBooks }}</p>
          </div>
          <div class="bg-white px-4 py-5 border-l-4 border-orange-400 shadow">
            <h2 class="font-bold text-gray-700 text-xl mb-3">Active Book Requests</h2>
            <p class="text-lg text-gray-500">{{ props.bookRequestCount }}</p>
          </div>
          <div class="bg-white px-4 py-5 border-l-4 border-purple-400 shadow">
            <h2 class="font-bold text-gray-700 text-xl mb-3">Total Sales</h2>
            <p class="text-lg text-gray-500">₱{{ props.sumTotalIncome }}.00</p>
          </div>
        </div>
        <!-- Chart Analytics -->
        <div class="grid grid-cols-1">
          <h2 class="text-gray-600 font-bold text-xl mb-2">Monthly Sales Analytics</h2>
          <div class="bg-white px-4 py-5 shadow border-l-4 border-indigo-400">
            <Bar v-if="loaded" :data="chartData" :options="chartOptions" />
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>