<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrump from '@/Components/Admin/Breadcrump.vue';
import ActionButton from '@/Components/Admin/ActionButton.vue';
import ModalForm from '@/Components/Admin/ModalForm.vue';
import { Head } from '@inertiajs/vue3';
// import { useForm } from '@inertiajs/inertia-vue3';


defineProps({
  categories: Object
})

</script>

<template>
  <Head title="Admin Dashboard" />
  <AdminLayout>
    <!-- Body-Content -->
    <div class="w-full h-full px-5  py-5 lg:px-10 lg:py-10">
      <div class="h-screen">
        <div class="flex items-center gap-2 text-2xl font-semibold text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
          </svg>
          <span>Book Categories</span>
        </div>
        <div class="mt-1 flex flex-col">
          <Breadcrump>
            Book Categories
          </Breadcrump>
          <div class="mt-3">
            <ActionButton>
              <p class="flex items-center text-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="mt-1 ms-2">Add Category</span>
              </p>
            </ActionButton>
            <ModalForm>
              Test
            </ModalForm>
          </div>
        </div>
        <div class="py-5">
          <div class="mx-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow sm:reounded-lg px-4 py-4">
              <div 
                v-if="$page.props.flash.message"
                class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" 
                role="alert">
                {{ $page.props.flash.message }}
              </div>
              <table class="table-fixed w-full">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border px-4 py-2 w-20 text-left">No.</th>
                    <th class="border px-4 py-2 text-left">Category Name</th>
                    <th class="border px-4 py-2 text-left">Status</th>
                    <th class="border px-4 py-2 text-left">Created Date</th>
                    <th class="border px-4 py-2 text-left">Updated Date</th>
                    <!-- <th class="px-4 py-2">Action</th> -->
                  </tr>
                </thead>
                <tbody>
                  <tr 
                  v-for="row in categories"
                  >
                    <td class="border px-4 py-2">{{ row.id }}</td>
                    <td class="border px-4 py-2">{{ row.category_name }}</td>
                    <td class="border px-4 py-2 text-white text-center" v-if="row.is_active == 1"><p class="bg-green-500 w-20 rounded">Active</p></td>
                    <td class="border px-4 py-2 text-white text-center" v-if="row.is_active == 0"><p class="bg-red-500 w-20 rounded">Inactive</p></td>
                    <td class="border px-4 py-2">{{ row.created_at }}</td>
                    <td class="border px-4 py-2">{{ row.updated_at }}</td>
                    <!-- <td class="border px-4 py-2">
                      <Link :href="route('category.create')">
                        <button
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded me-5">Edit</button>
                      </Link>
                      <button @click="deleteRow(row)"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                    </td> -->
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>