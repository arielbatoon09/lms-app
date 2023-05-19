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
const searchTerm = ref('');
const selectedCategory = ref('All');

computed(() => {
    const uniqueCategories = new Set();
    uniqueCategories.add('All'); // Add 'All Categories' option

    for (const book of Object.values(props.books)) {
        uniqueCategories.add(book.category);
    }

    return [''].concat([...uniqueCategories]);
});

const filteredBooks = computed(() => {
    const filtered = Object.values(props.books).filter((book) => {
        const searchTermMatch =
            book.category &&
            book.category.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            book.name &&
            book.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            book.description &&
            book.description.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            book.author &&
            book.author.toLowerCase().includes(searchTerm.value.toLowerCase());

        const categoryMatch =
            selectedCategory.value === '' || selectedCategory.value === 'All' || selectedCategory.value === book.category;

        return searchTermMatch && categoryMatch;
    });

    return filtered;
});


// To get request
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
    <Head title="Browse Books" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browse Books</h2>
        </template>
        <!-- Books Display -->
        <div class="px-6 py-10 mx-auto max-w-screen-xl">
            <div class="flex justify-between mb-4 flex-col md:flex-row gap-3">
                <input type="text" class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none"
                    v-model="searchTerm" placeholder="Search books">
                <div>
                    <select class="appearance-none w-full md:w-48 px-4 py-2 border border-gray-300 rounded-md "
                        v-model="selectedCategory">
                        <option value="All">All</option>
                        <option v-for="row in books" :value="row.category" :key="row.id">{{ row.category }}</option>
                    </select>
                </div>
            </div>
            <div v-if="filteredBooks.length === 0" class="w-full px-1">
                <p class="text-gray-500">Not Found</p>
            </div>
            <div class="grid grid-cols-1 gap-4 md:gap-8 md:grid-cols-2 xl:grid-cols-3">
                <div class="bg-white shadow px-5 py-6 rounded-lg cursor-pointer relative" v-for="row in filteredBooks"
                    :key="row.id" @click="goBookDetails(row.id, $page.props.auth.user.id)">
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
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
