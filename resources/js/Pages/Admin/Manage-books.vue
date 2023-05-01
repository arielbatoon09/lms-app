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

// Declaration
const showModal = ref(false);
const isClose = ref(true);
const optionAuthors = ref('disabled');
const optionCategories = ref('disabled');
const selectedStatus = ref(1);
const path = ref('/uploads/');

// DataTables
DataTable.use(DataTablesCore);

// Retrieve Data from Backend props
defineProps({
  books: Object,
  categories: Object,
  authors: Object
});

// Handling Fileuploads

const formModal = reactive({
  isOpen: null,
});

const form = useForm({
  book_img: null,
  book_name: null,
  description: null,
  category_id: null,
  author_id: null,
  book_fees: null,
  quantity: null,
  is_active: null,
});

const editForm = useForm({
  book_img: null,
  book_name: null,
  description: null,
  category_id: null,
  author_id: null,
  book_fees: null,
  quantity: null,
  is_active: null,
});

const setBook = (id, book_img, book_name, description, category_id, author_id, quantity, book_fees, is_active) => {
  formModal.isOpen = id;
  editForm.book_name = book_name;
  editForm.description = description;
  optionCategories.value = category_id;
  optionAuthors.value = author_id;
  editForm.book_fees = book_fees;
  editForm.quantity = quantity;
  editForm.is_active = is_active;
};

const addBook = () => {
  if (form.book_fees == null) {
    form.book_fees = 0;
  }
  if (optionAuthors.value == 'disabled') {
    optionAuthors.value = null;
  }
  if (optionCategories.value == 'disabled') {
    optionCategories.value == null;
  }
  form.is_active = 1;
  form.category_id = optionCategories.value;
  form.author_id = optionAuthors.value;
  form.post('/admin/add-book');
  form.reset();
  window.location.reload()
};

const deleteBook = (id) => {
  if (window.confirm("Are you sure you want to delete this item?")) {
    form.post(`/admin/delete-book/${id}`);
    form.reset();
    window.location.reload()
  }
};

const updateBook = (id) => {
  formModal.isOpen = null;
  editForm.is_active = selectedStatus.value;
  editForm.category_id = optionCategories.value;
  editForm.author_id = optionAuthors.value;
  editForm.post(`/admin/update-book/${id}`);
  editForm.reset();
  window.location.reload();
};
</script>

<template>
  <Head title="Admin Dashboard" />
  <AdminLayout>
    <!-- Body-Content -->
    <div class="w-full h-fullpx-5 py-5 lg:px-10 lg:py-10 bg-gray-50">
      <div class="h-full">
        <div class="flex items-center gap-2 text-2xl font-semibold text-gray-700">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
          </svg>
          <span>Manage Books</span>
        </div>
        <div class="mt-1 flex flex-col">
          <Breadcrump>
            Manage Books
          </Breadcrump>
          <div class="mt-3">
            <ActionButton @click="showModal = true">
              <p class="flex items-center text-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="mt-1 ms-2">Add Book</span>
              </p>
            </ActionButton>
            <!-- Modal Form -->
            <ModalForm v-show="showModal">
              <form @submit.prevent="addBook">
                <div class="mb-4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                  <div class="mb-3">
                    <label for="formFile" class="block text-gray-500 text-sm font-bold mb-2">Add Image</label>
                    <input
                      class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary"
                      @input="form.book_img = $event.target.files[0]" type="file" id="formFile" />
                  </div>
                  <div class="mb-3">
                    <label for="book_name" class="block text-gray-500 text-sm font-bold mb-2">Book Information</label>
                    <input type="text"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      v-model="form.book_name" id="book_name" placeholder="Name of the book" />
                  </div>
                  <div class="mb-2">
                    <textarea type="text"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-[100px]"
                      v-model="form.description" id="description" placeholder="Write book description...">
                    </textarea>
                  </div>
                  <div class="mb-2">
                    <select v-model="optionAuthors"
                      class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 mt-3 text-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                      <option value="disabled" disabled>Choose authors</option>
                      <option v-for="author in authors" :key="author.id" :value="author.id">{{ author.author_name }}
                      </option>
                    </select>
                  </div>
                  <div class="mb-2">
                    <select v-model="optionCategories"
                      class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 mt-3 text-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                      <option value="disabled" disabled>Choose categories</option>
                      <option v-for="category in categories" :key='category.id' :value="category.id">{{
                        category.category_name }}
                      </option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <input type="number"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      v-model="form.quantity" id="quantity" placeholder="Book Qty." />
                  </div>
                  <div class="mb-3">
                    <input type="number"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                      v-model="form.book_fees" id="book_fees" placeholder="Fees ₱ (Optional)" />
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
                      <th class="border px-4 py-2 text-left">Image</th>
                      <th class="border px-4 py-2 text-left">Book Name</th>
                      <th class="border px-4 py-2 text-left">Category</th>
                      <th class="border px-4 py-2 text-left">Author</th>
                      <th class="border px-4 py-2 text-left">Qty</th>
                      <th class="border px-4 py-2 text-left">Fees ₱</th>
                      <th class="border px-4 py-2 text-left">Status</th>
                      <th class="px-3 py-2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(row, index) in books" :key="row.id">
                      <td class="border px-4 py-2">{{ index+1 }}</td>
                      <td class="border px-4 py-2 w-20"><img :src="path + row.book_img"
                          style="width: 64px; height: 64px;" /></td>
                      <td class="border px-4 py-2">{{ row.book_name }}</td>
                      <td class="border px-4 py-2">{{ row.category }}</td>
                      <td class="border px-4 py-2">{{ row.author }}</td>
                      <td class="border px-4 py-2">{{ row.quantity }}</td>
                      <td class="border px-4 py-2">₱{{ row.book_fees }}</td>
                      <td class="border px-4 py-2 text-white text-center" v-if="row.is_active == 1">
                        <p class="bg-green-500 w-20 rounded">Active</p>
                      </td>
                      <td class="border px-4 py-2 text-white text-center" v-if="row.is_active == 0">
                        <p class="bg-red-500 w-20 rounded">Inactive</p>
                      </td>
                      <td class="border px-4 py-2 h-20">
                        <!-- Actions -->
                        <div class="flex justify-center gap-2">
                          <!-- View Action -->
                          <!-- <button class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-full me-2"
                          @click="setBook(row.id, row.book_img, row.book_name, row.description, row.category_id, row.author_id ,row.quantity, row.book_fees)">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 25 25" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          </svg>
                        </button> -->
                          <!-- Edit Action -->
                          <button class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full rounded"
                            @click="setBook(row.id, row.book_img, row.book_name, row.description, row.category_id, row.author_id, row.quantity, row.book_fees, row.is_active)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 25 25" stroke-width="1.5"
                              stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                          </button>
                          <!-- Delete Action -->
                          <button class="bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-full"
                            @click="deleteBook(row.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 25 25" stroke-width="1.5"
                              stroke="currentColor" class="w-5 h-5">
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
                <form @submit.prevent="setBook()">
                  <div class="mb-4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mb-3">
                      <label for="formFile" class="block text-gray-500 text-sm font-bold mb-2">Update Image</label>
                      <input
                        class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary"
                        @input="editForm.book_img = $event.target.files[0]" type="file" id="formFile" />
                    </div>
                    <div class="mb-3">
                      <label for="book_name" class="block text-gray-500 text-sm font-bold mb-2">Book Information</label>
                      <input type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        v-model="editForm.book_name" id="book_name" placeholder="Name of the book" />
                    </div>
                    <div class="mb-2">
                      <textarea type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-[100px]"
                        v-model="editForm.description" id="description" placeholder="Write book description...">
                      </textarea>
                    </div>
                    <div>
                      <select v-model="selectedStatus"
                        class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 mt-3 text-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option value="1">Activate</option>
                        <option value="0">Deactivate</option>
                      </select>
                    </div>
                    <div class="mb-2">
                      <select v-model="optionAuthors"
                        class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 mt-3 text-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option value="disabled" disabled>Choose authors</option>
                        <option v-for="author in authors" :key="author.id" :value="author.id">{{ author.author_name }}
                        </option>
                      </select>
                    </div>
                    <div class="mb-2">
                      <select v-model="optionCategories"
                        class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 mt-3 text-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                        <option value="disabled" disabled>Choose categories</option>
                        <option v-for="category in categories" :key='category.id' :value="category.id">{{
                          category.category_name }}
                        </option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="quantity" class="text-gray-500">Quantity:</label>
                      <input type="number"
                        class="shadow appearance-none border rounded w-full py-2 px-3 mt-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        v-model="editForm.quantity" id="quantity" placeholder="Book Qty." />
                    </div>
                    <div class="mb-3">
                      <label for="book_fees" class="text-gray-500">Book Fee ₱</label>
                      <input type="number"
                        class="shadow appearance-none border rounded w-full py-2 px-3 mt-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        v-model="editForm.book_fees" id="book_fees" placeholder="Fees ₱ (Optional)" />
                    </div>
                  </div>
                  <!-- Actions -->
                  <div class="bg-gray-100 px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto flex flex-row gap-2">
                      <button type="submit" :disabled="editForm.processing" @click="updateBook(formModal.isOpen)"
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