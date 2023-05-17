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
    books: Object,
});

const formModal = reactive({
    isOpen: null,
});

const form = useForm({
    id: null,
    status: null,
});

const getID =(id)=> {
    formModal.isOpen = true;
    form.id = id;
}

const updateStatus =()=> {
    form.status = selectedStatus.value;
    formModal.isOpen = null;
    form.put(route('admin-book-request.update'));
    form.reset();
    window.location.reload();
}

const deleteBookRequest =(id)=> {
    form.id = id;
    if (window.confirm("Are you sure you want to delete this item?")) {
        form.delete(route('admin-book-request.destroy'));
        form.reset();
        window.location.reload();
  }
}

</script>

<template>
    <Head title="Admin Dashboard" />
    <AdminLayout>
        <!-- Body-Content -->
        <div class="w-full h-fullpx-5 px-5 lg:px-14 py-10 bg-gray-50">
            <div class="h-full">
                <div class="flex items-center gap-2 text-2xl font-semibold text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                    </svg>
                    <span>Book Request</span>
                </div>
                <div class="mt-1 flex flex-col">
                    <Breadcrump>
                        Book Request
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
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div style="overflow-x:auto;">
                                <DataTable class="display nowrap h-full">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border px-4 py-2 w-5 text-left">No.</th>
                                            <th class="border px-4 py-2 text-left">Image</th>
                                            <th class="border px-4 py-2 w-0 text-left">Issued No.</th>
                                            <th class="border px-4 py-2 text-left">Borrower</th>
                                            <th class="border px-4 py-2 text-left">Book Name</th>
                                            <th class="border px-4 py-2 text-left">Fees ₱</th>
                                            <th class="border px-4 py-2 text-left">To Return</th>
                                            <th class="border px-4 py-2 text-left">Status</th>
                                            <th class="px-3 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in books" :key="row.id">
                                            <td class="border px-4 py-2">{{ index+1 }}</td>
                                            <td class="border px-4 py-2 w-20"><img :src="path+row.book_img"
                                                    style="width: 64px; height: 64px;" /></td>
                                            <td class="border px-4 py-2">{{ row.id }}</td>            
                                            <td class="border px-4 py-2">{{ row.user_name }}</td>        
                                            <td class="border px-4 py-2">{{ row.book_name }}</td>
                                            <td class="border px-4 py-2">₱ {{ row.book_fees }}.00</td>
                                            <td class="border px-4 py-2">{{ row.to_return }}</td>
                                            <td class="border px-4 py-2 text-white text-center" v-if="row.status == 1">
                                                <p class="bg-green-500 w-20 rounded">Approved</p>
                                            </td>
                                            <td class="border px-4 py-2 text-white text-center" v-if="row.status == 2">
                                                <p class="bg-orange-500 w-20 rounded">Pending</p>
                                            </td>
                                            <td class="border px-4 py-2 text-white text-center" v-if="row.status == 0">
                                                <p class="bg-red-500 w-20 rounded">Rejected</p>
                                            </td>
                                            <td class="border px-4 py-2 h-20">
                                                <!-- Actions -->
                                                <div class="flex justify-center gap-2">
                                                    <!-- Edit Action -->
                                                    <button v-show="row.status != 1"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full rounded"
                                                        @click="getID(row.id)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 25 25" stroke-width="1.5" stroke="currentColor"
                                                            class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                        </svg>
                                                    </button>
                                                    <!-- Delete Action -->
                                                    <button
                                                        class="bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-full"
                                                        @click="deleteBookRequest(row.id)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 25 25" stroke-width="1.5" stroke="currentColor"
                                                            class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
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
                                                <option value="disabled" disabled>Select status</option>
                                                <option value="1">Approve</option>
                                                <option value="0">Reject</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Actions -->
                                    <div class="bg-gray-100 px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <span
                                            class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto flex flex-row gap-2">
                                            <button type="submit" 
                                                :disabled="form.processing"
                                                @click="updateStatus()"
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
}</style>