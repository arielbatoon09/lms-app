<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { reactive, ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const selectMOD = reactive('disabled');

// Retrieve Data from Backend props
const props = defineProps({
    invoice: Object,
});

const $toast = useToast();

const form = useForm({
    id: null,
    book_fees: null,
    mop: null,
})


// Pagination data
const currentPage = ref(1);
const perPage = 10;

// Computed property for sliced rows
const slicedRows = computed(() => {
    const rows = props.invoice || []; // Access the invoice prop directly
    const startIndex = (currentPage.value - 1) * perPage;
    const endIndex = startIndex + perPage;
    return rows.slice(startIndex, endIndex);
});

// Total pages calculation
const totalPages = computed(() => {
    const rows = props.invoice || []; // Access the invoice prop directly
    return Math.ceil(rows.length / perPage);
});

// Pagination methods
function nextPage() {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
}

function previousPage() {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
}

const handleMOD = (selectMOD, id, book_fees) => {
    form.id = id;
    form.book_fees = book_fees;
    form.mop = selectMOD;

    if (selectMOD == 'Paypal') {
        Swal.fire({
            title: 'Do you want to use PayPal?',
            text: "Ask for support if there's any issue encountered!",
            imageUrl: 'https://img.freepik.com/free-icon/paypal_318-674245.jpg',
            imageWidth: 200,
            imageHeight: 200,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.post(route('invoices.makePayments'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        form.reset()

                    },
                    onError: () => {
                        $toast.error('Please try again!', {
                            duration: 2000,
                        });
                    },
                });
            }
        })
    } else if (selectMOD == 'Over-The-Counter') {
        Swal.fire({
            title: 'Pay Over-The-Counter?',
            text: "Pay your invoice directly!",
            imageUrl: 'https://d2v9ipibika81v.cloudfront.net/uploads/sites/281/IMG_4107.jpeg',
            imageWidth: 400,
            imageHeight: 200,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continue',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.post(route('invoices.makePayments'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        form.reset();
                        $toast.success('Pay your invoice Over-The-Counter!', {
                            duration: 2000,
                        });
                    },
                    onError: () => {
                        $toast.error('Please try again!', {
                            duration: 2000,
                        });
                    },
                });
            }
        })
    }
}

</script>

<template>
    <Head title="Invoices" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoices</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:h-screen">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-md text-gray-700 bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 w-1">
                                        #
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%">
                                        Book ID
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Book Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Book Fee
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 20%;">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3" style="width: 10%;">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700"
                                    v-for="(row, index) in slicedRows" :key="row.id">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ (currentPage - 1) * perPage + index + 1 }}
                                    </th>
                                    <td class="px-6 py-4">
                                        BK-{{ row.book_id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ row.book_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        ₱ {{ row.book_fees }}.00
                                    </td>
                                    <td class="px-6 py-4" v-if="row.action == 0">
                                        <span class="py-1 px-3 rounded-lg text-red-600 bg-red-50">Unpaid</span>
                                    </td>
                                    <td class="px-6 py-4" v-else-if="row.action == 1">
                                        <span class="py-1 px-3 rounded-lg text-green-600 bg-green-50">Paid</span>
                                    </td>
                                    <td class="px-6 py-4" v-else-if="row.action == 2">
                                        <span
                                            class="py-1 px-3 rounded-lg text-orange-600 bg-orange-50 whitespace-nowrap">Over-The-Counter</span>
                                    </td>
                                    <td class="px-6 py-4" v-if="row.action == 0">
                                        <select v-model="selectMOD" @change="handleMOD(selectMOD, row.id, row.book_fees)"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pe-8 px-4 py-2.3">
                                            <option value="disabled" disabled>Select Payment</option>
                                            <option value="Paypal">PayPal</option>
                                            <option value="Over-The-Counter">Over-The-Counter</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4" v-else-if="row.action == 1">
                                        <p>Completed</p>
                                    </td>
                                    <td class="px-6 py-4" v-else-if="row.action == 2">
                                        <p>Pay Over-The-Counter</p>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <nav class="my-4 mx-6 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <button @click="previousPage" :disabled="currentPage === 1"
                                    class="px-3 py-1 border rounded-lg"
                                    :class="{ 'text-gray-400': currentPage === 1 }">Previous</button>
                                <button @click="nextPage" :disabled="currentPage === totalPages"
                                    class="px-3 py-1 border rounded-lg"
                                    :class="{ 'text-gray-400': currentPage === totalPages }">Next</button>
                            </div>
                            <div>
                                <span class="text-gray-500">{{ currentPage }} / {{ totalPages }}</span>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
