<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const path = ref('/uploads/');
// Retrieve Data from Backend props
const props = defineProps({
    books: Object,
});

// Search Feature
const selectedStatus = ref('3');

const filteredBooks = computed(() => {
  const filtered = Object.values(props.books).filter((book) => {
    if (selectedStatus.value === '0') {
      return book.is_return === 0;
    } else if (selectedStatus.value === '1') {
      return book.is_return === 1;
    } else if (selectedStatus.value === '2') {
      return book.is_return === 2;
    } else {
      return true; // Show all books if no specific status is selected
    }
  });

  return filtered;
});

const form = useForm({
    id: null,
    user_id: null,
});

const goBookDetails = (id, user_id) => {
    form.user_id = user_id;
    form.get(`/book-details/${id}`);
    form.reset();
};
</script>

<template>
    <Head title="Book Issued" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Book Issued List</h2>
        </template>
        <!-- Books Display -->
        <div class="px-6 py-10 mx-auto max-w-screen-xl">
            <div class="flex justify-between mb-4 flex-col md:flex-row gap-3">
                <div>
                    <select class="appearance-none w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md "
                        v-model="selectedStatus">
                        <option value="3">All</option>
                        <option value="0">On-going</option>
                        <option value="1">Returned</option>
                        <option value="2">Overdue</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 md:gap-8 md:grid-cols-2 xl:grid-cols-3">
                <div class="bg-white shadow px-5 py-6 rounded-lg cursor-pointer relative" v-for="row in filteredBooks" :key="row.id"
                    @click="goBookDetails(row.book_id, $page.props.auth.user.id)">
                    <span class="absolute bg-blue-100 top-3 px-2 text-blue-600 rounded-lg">Tap to see details</span>
                    <img class="object-cover object-center w-full h-64 rounded-lg lg:h-70" :src="path + row.book_img" />
                    <div class="mt-8">
                        <!-- Book Category -->
                        <span class="text-blue-500 font-normal">{{ row.category }}</span>
                        <!-- Book Title -->
                        <h3 class="mt-2 text-xl font-semibold text-gray-800 dark:text-white">
                            {{ row.book_name }}
                        </h3>
                        <p class="text-gray-500">{{ row.author }}</p>
                        <p class="mt-2 text-gray-500 dark:text-gray-400 truncate">
                            <span class="text-gray-500 font-semibold">Book Description<br></span>
                            {{ row.description }}
                        </p>
                        <p class="text-gray-600 rounded-md mt-3 font-bold" v-if="row.is_return != 1">To return: <span
                                class="font-normal">{{ row.to_return }}</span></p>
                        <p class="text-gray-600 rounded-md mt-1 font-bold" v-if="row.is_return == 0">Status: <span
                                class="font-normal text-yellow-500">On-going</span></p>
                        <p class="text-gray-600 rounded-md mt-1 font-bold" v-if="row.is_return == 1">Status: <span
                                class="font-normal text-green-500">Returned</span></p>
                        <p class="text-gray-600 rounded-md mt-1 font-bold" v-if="row.is_return == 2">Status: <span
                                class="font-normal text-red-500">Overdue</span></p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout></template>
