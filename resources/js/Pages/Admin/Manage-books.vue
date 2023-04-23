<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrump from '@/Components/Admin/Breadcrump.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Admin Dashboard" />
  <AdminLayout>
    <!-- Body-Content -->
    <div class="w-full h-full px-5  py-5 lg:px-10 lg:py-10">
      <div class="h-screen">
        <div class="flex items-center gap-2 text-2xl font-semibold text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor"
            class="w-6 h-6">
            <path strokeLinecap="round" strokeLinejoin="round"
              d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
          </svg>
          <span>List of Books</span>
        </div>
        <div class="mt-1">
          <Breadcrump>
            Manage Books
          </Breadcrump>
        </div>
        <div class="mt-5">
        </div>
      </div>
    </div>
  </AdminLayout>
</template>