<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Breadcrump from '@/Components/Admin/Breadcrump.vue';
import ModalForm from '@/Components/Admin/ModalForm.vue';
import { ref, reactive } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import 'datatables.net-responsive';

// Declaration
const isClose = ref(true);
const selectedStatus = ref('disabled');
const path = ref('/uploads/');

// DataTables
DataTable.use(DataTablesCore);

// Retrieve Data from Backend props
defineProps({
  transactions: Object,
});

const formModal = reactive({
  isOpen: null,
});

const form = useForm({
  id: null,
  action: null,
});

const getID = (id) => {
  formModal.isOpen = true;
  form.id = id;
}

const updateStatus = () => {
  form.action = selectedStatus.value;
  formModal.isOpen = null;
  form.put(route('transactions.update'));
  form.reset();
  window.location.reload();
}

</script>

<template>
  <Head title="Admin Dashboard" />
  <AdminLayout>
    <!-- Body-Content -->
    <div class="w-full h-full px-5 lg:px-14 py-10 bg-gray-50">
      <div class="h-full">
        <div class="flex items-center gap-2 text-2xl font-semibold text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
          </svg>
          <span>Transactions</span>
        </div>
        <div class="mt-1 flex flex-col">
          <Breadcrump>
            Transactions
          </Breadcrump>
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
                      <th class="border px-4 py-2 w-0 text-left">Book No.</th>
                      <th class="border px-4 py-2 w-0 text-left">User Name</th>
                      <th class="border px-4 py-2 text-left">Book Name</th>
                      <th class="border px-4 py-2 text-left">Book Fee</th>
                      <th class="border px-4 py-2 text-left">Status</th>
                      <th class="px-3 py-2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(row, index) in transactions" :key="row.id">
                      <td class="border px-4 py-2">{{ index + 1 }}</td>
                      <td class="border px-4 py-2">BK-{{ row.book_id }}</td>    
                      <td class="border px-4 py-2">{{ row.user_name }}</td>
                      <td class="border px-4 py-2">{{ row.book_name }}</td>
                      <td class="border px-4 py-2">₱ {{ row.book_fees }}.00</td>
                      <td class="border px-4 py-2 text-white text-center" v-if="row.action == 1">
                        <p class="bg-green-500 rounded">Paid</p>
                      </td>
                      <td class="border px-4 py-2 text-white text-center" v-if="row.action == 2">
                        <p class="bg-orange-500 rounded">Over-The-Counter</p>
                      </td>
                      <td class="border px-4 py-2 text-white text-center" v-if="row.action == 0">
                        <p class="bg-red-500 rounded">Unpaid</p>
                      </td>
                      <td class="border px-4 py-2 h-20">
                        <!-- Actions -->
                        <div class="flex justify-center gap-2">
                          <!-- Edit Action -->
                          <button v-show="row.action != 1"
                            class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full rounded"
                            @click="getID(row.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 25 25" stroke-width="1.5"
                              stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                          </button>
                          <p v-show="row.action == 1">
                            Completed
                          </p>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </DataTable>
              </div>
              <!-- Edit-Modal -->
              <!-- The modal is being separated to the table due to DataTables functionality -->
              <ModalForm v-show="formModal.isOpen" class="display nowrap">
                <form @submit.prevent="updateStatus">
                  <div class="mb-4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div>
                      <span>Select Action:</span>
                      <select v-model="selectedStatus"
                        class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 mt-3 text-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option value="disabled" disabled>Select</option>
                        <option value="1">Mark as Paid</option>
                      </select>
                    </div>
                  </div>
                  <!-- Actions -->
                  <div class="bg-gray-100 px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto flex flex-row gap-2">
                      <button type="submit" :disabled="form.processing" @click="updateStatus()"
                        class="inline-flex justify-center w-full rounded-md border border-transparent px-8 py-2 bg-blue-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-600 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        Update
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