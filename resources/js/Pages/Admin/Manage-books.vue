<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Breadcrump from '@/Components/Admin/Breadcrump.vue';
import Modal from '@/Components/Admin/ModalForm.vue';
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
          <PrimaryButton>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span class="ms-3">Add Book</span>
          </PrimaryButton>
          <Modal>
            Test
          </Modal>
        </div>
        <div class="mt-5">
          <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
              <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                  <th scope="col" class="px-6 py-3 rounded-l-lg">
                    Product name
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Qty
                  </th>
                  <th scope="col" class="px-6 py-3 rounded-r-lg">
                    Price
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="bg-white dark:bg-gray-800">
                  <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Apple MacBook Pro 17"
                  </th>
                  <td class="px-6 py-4">
                    1
                  </td>
                  <td class="px-6 py-4">
                    $2999
                  </td>
                </tr>
                <tr class="bg-white dark:bg-gray-800">
                  <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Microsoft Surface Pro
                  </th>
                  <td class="px-6 py-4">
                    1
                  </td>
                  <td class="px-6 py-4">
                    $1999
                  </td>
                </tr>
                <tr class="bg-white dark:bg-gray-800">
                  <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Magic Mouse 2
                  </th>
                  <td class="px-6 py-4">
                    1
                  </td>
                  <td class="px-6 py-4">
                    $99
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="font-semibold text-gray-900 dark:text-white">
                  <th scope="row" class="px-6 py-3 text-base">Total</th>
                  <td class="px-6 py-3">3</td>
                  <td class="px-6 py-3">21,000</td>
                </tr>
              </tfoot>
            </table>
          </div>

        </div>

      </div>
    </div>
</AdminLayout></template>