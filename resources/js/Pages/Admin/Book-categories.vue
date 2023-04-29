<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrump from '@/Components/Admin/Breadcrump.vue';
import ActionButton from '@/Components/Admin/ActionButton.vue';
import ModalForm from '@/Components/Admin/ModalForm.vue';
import { ref, reactive } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';

const showModal = ref(false);
const isClose = ref(true);
const selectedStatus = ref(1); 

// Retrieve Data from Backend props
defineProps({
  categories: Object
});


const form = useForm({
  category_name: null,
  is_active: null,
});

const editForm = useForm({
  category_name: null,
  is_active: null,
});

const formModal = reactive({
  isOpen: null,
});

const setCategory =(category, id)=> {
  formModal.isOpen = id; 
  editForm.category_name = category;
};

const addCategory = () => {
  form.is_active = 1;
  form.post('/admin/add-category');
  form.reset();
};

const deleteCategory = (id) => {
  if (window.confirm("Are you sure you want to delete this item?")) {
    form.post(`/admin/delete-category/${id}`);
    form.reset();
  }
};

const updateCategory = (id) => {
  formModal.isOpen = null;
  editForm.is_active = selectedStatus.value;
  editForm.patch(`/admin/update-category/${id}`);
  editForm.reset();
}


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
            <ActionButton @click="showModal = true">
              <p class="flex items-center text-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="mt-1 ms-2">Add Category</span>
              </p>
            </ActionButton>
            <!-- Modal Form -->
            <ModalForm v-show="showModal">
              <form @submit.prevent="addCategory">
                <div class="mb-4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                  <div>
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Add Category</label>
                    <input type="text"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      v-model="form.category_name"
                      id="category_name" 
                      placeholder="Enter category name"
                      />
                  </div>
                </div>
                <!-- Actions -->
                <div class="bg-gray-100 px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                  <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto flex flex-row gap-2">
                    <button type="submit" :disabled="form.processing" @click="showModal = false"
                      class="inline-flex justify-center w-full rounded-md border border-transparent px-8 py-2 bg-blue-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                      Save
                    </button>
                    <span @click="showModal = false"
                      class="cursor-pointer inline-flex justify-center w-full rounded-md border border-transparent px-8 py-2 bg-gray-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                      Close
                    </span>
                  </span>
                </div>
              </form>
            </ModalForm>
          </div>
        </div>
        <div class="py-5">
          <div class="mx-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow sm:reounded-lg px-4 py-4">
              <!-- Flash Message -->
              <div v-if="$page.props.flash.message"
                class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3 flex flex-row justify-between items-start"
                v-show="isClose" role="alert">
                {{ $page.props.flash.message }}
                <button class="text-gray-500 hover:text-blue-400" @click="isClose = false">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <table class="table-fixed w-full">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border px-4 py-2 w-20 text-left">No.</th>
                    <th class="border px-4 py-2 text-left">Category Name</th>
                    <th class="border px-4 py-2 text-left">Status</th>
                    <th class="border px-4 py-2 text-left">Created Date</th>
                    <th class="border px-4 py-2 text-left">Updated Date</th>
                    <th class="px-3 py-2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="row in categories" :key="row.id">
                    <td class="border px-4 py-2">{{ row.id }}</td>
                    <td class="border px-4 py-2">{{ row.category_name }}</td>
                    <td class="border px-4 py-2 text-white text-center" v-if="row.is_active == 1">
                      <p class="bg-green-500 w-20 rounded">Active</p>
                    </td>
                    <td class="border px-4 py-2 text-white text-center" v-if="row.is_active == 0">
                      <p class="bg-red-500 w-20 rounded">Inactive</p>
                    </td>
                    <td class="border px-4 py-2">{{ row.created_at }}</td>
                    <td class="border px-4 py-2">{{ row.updated_at }}</td>
                    <td class="border px-4 py-2 flex justify-center" >
                      <!-- Edit Form Action -->
                      <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded me-2"
                        @click="setCategory(row.category_name, row.id)">
                        Edit
                      </button>
                      <!-- Edit Modal -->
                      <ModalForm v-show="formModal.isOpen == row.id" >
                        <form @submit.prevent="updateCategory(row.id)">
                          <div class="mb-4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div>
                              <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Edit Category</label>
                              <input type="text"
                              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                              v-model="editForm.category_name"
                              id="category_name" placeholder="New category name"/>
                            </div>
                            <div>
                              <select
                              v-model="selectedStatus"
                              class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 mt-3 text-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value="1">Activate</option>
                                <option value="0">Deactivate</option>
                              </select>
                            </div>
                          </div>
                          <!-- Actions -->
                          <div class="bg-gray-100 px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto flex flex-row gap-2">
                              <button type="submit" 
                              :disabled="editForm.processing" 
                              @click="updateCategory(row.id)"
                                class="inline-flex justify-center w-full rounded-md border border-transparent px-8 py-2 bg-blue-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Save
                              </button>
                              <span @click="formModal.isOpen = null"
                                class="cursor-pointer inline-flex justify-center w-full rounded-md border border-transparent px-8 py-2 bg-gray-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Close
                              </span>
                            </span>
                          </div>
                        </form>
                      </ModalForm>
                      <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"
                        @click="deleteCategory(row.id)">
                        Delete
                      </button>
                    </td>
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