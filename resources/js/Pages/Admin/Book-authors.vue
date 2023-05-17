<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrump from '@/Components/Admin/Breadcrump.vue';
import ActionButton from '@/Components/Admin/ActionButton.vue';
import ModalForm from '@/Components/Admin/ModalForm.vue';
import { ref, reactive } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import 'datatables.net-responsive';

const showModal = ref(false);
const isClose = ref(true);
const selectedStatus = ref(1);

// DataTables
DataTable.use(DataTablesCore);

// Retrieve Data from Backend props
defineProps({
  authors: Object
});


const formModal = reactive({
  isOpen: null,
});

const form = useForm({
  author_name: null,
});

const editForm = useForm({
  author_name: null,
});

const setAuthor = (author, id) => {
  formModal.isOpen = id;
  editForm.author_name = author;
};

const addAuthor = () => {
  form.is_active = 1;
  form.post('/admin/add-author');
  form.reset();
  window.location.reload()
};

const deleteAuthor = (id) => {
  if (window.confirm("Are you sure you want to delete this item?")) {
    form.post(`/admin/delete-author/${id}`);
    form.reset();
    window.location.reload()
  }
};

const updateAuthor = (id) => {
  formModal.isOpen = null;
  editForm.is_active = selectedStatus.value;
  editForm.patch(`/admin/update-author/${id}`);
  editForm.reset();
  window.location.reload()
};
</script>

<template>
  <Head title="Admin Dashboard" />
  <AdminLayout>
    <!-- Body-Content -->
    <div class="w-full h-fullpx-5 px-5 lg:px-14 py-10 bg-gray-50">
      <div class="h-full">
        <div class="flex items-center gap-2 text-2xl font-semibold text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
          </svg>
          <span>Book Authors</span>
        </div>
        <div class="mt-1 flex flex-col">
          <Breadcrump>
            Book Authors
          </Breadcrump>
          <div class="mt-3">
            <ActionButton @click="showModal = true">
              <p class="flex items-center text-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="mt-1 ms-2">Add Author</span>
              </p>
            </ActionButton>
            <!-- Modal Form -->
            <ModalForm v-show="showModal">
              <form @submit.prevent="addAuthor">
                <div class="mb-4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                  <div>
                    <label for="author_name" class="block text-gray-700 text-sm font-bold mb-2">Add Author</label>
                    <input type="text"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      v-model="form.author_name" id="author_name" placeholder="Enter author name" />
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
              <div style="overflow-x:auto;">
                <DataTable class="display nowrap h-full">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border px-4 py-2 w-5 text-left">No.</th>
                    <th class="border px-4 py-2 text-left">Category Name</th>
                    <th class="border px-4 py-2 text-left">Created Date</th>
                    <th class="border px-4 py-2 text-left">Updated Date</th>
                    <th class="px-3 py-2">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(row, index) in authors" :key="row.id">
                    <td class="border px-4 py-2">{{ index+1 }}</td>
                    <td class="border px-4 py-2">{{ row.author_name }}</td>
                    <td class="border px-4 py-2">{{ row.created_at }}</td>
                    <td class="border px-4 py-2">{{ row.updated_at }}</td>
                    <td class="border px-4 py-2 flex justify-center">
                      <!-- Actions -->
                      <!-- Edit Action -->
                      <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold p-2 rounded-full me-2"
                        @click="setAuthor(row.author_name, row.id)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 25 25" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                      </button>
                      <!-- Delete Action -->
                      <button class="bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-full"
                        @click="deleteAuthor(row.id)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 25 25" stroke-width="1.5"
                              stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </DataTable>                
              </div>
              <!-- Edit-Modal -->
              <!-- The modal is being separated to the table due to DataTables functionality -->
              <ModalForm v-show="formModal.isOpen" class="display nowrap">
                <form @submit.prevent="updateAuthor()">
                  <div class="mb-4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div>
                      <label for="author_name" class="block text-gray-700 text-sm font-bold mb-2">Edit Author</label>
                      <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        v-model="editForm.author_name" id="author_name" placeholder="New category name" />
                    </div>
                  </div>
                  <!-- Actions -->
                  <div class="bg-gray-100 px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto flex flex-row gap-2">
                      <button type="submit" :disabled="editForm.processing" @click="updateAuthor(formModal.isOpen)"
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style>
@import 'datatables.net-dt';

.dataTables_wrapper .dataTables_filter input {
  border: 1px solid #D9D9D9;
  margin-bottom: 10px;
}
</style>