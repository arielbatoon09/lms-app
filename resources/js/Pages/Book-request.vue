<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// Retrieve Data from Backend props
defineProps({
    books: Object,
});

const path = ref('/uploads/');
</script>

<template>
    <Head title="Book Request" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Book Request List</h2>
        </template>
        <!-- Books Display -->
        <div class="px-6 py-10 mx-auto max-w-screen-xl">
            <div class="grid grid-cols-1 gap-4 md:gap-8 md:grid-cols-2 xl:grid-cols-3">
                <div class="bg-white shadow px-5 py-6 rounded-lg relative" v-for="row in books" :key="row.id">
                    <img class="object-cover object-center w-full h-64 rounded-lg lg:h-70" :src="path + row.book_img" />
                    <div class="mt-8">
                        <!-- Book Category -->
                        <span class="text-blue-500 font-normal">{{ row.category_name }}</span>
                        <!-- Book Title -->
                        <h3 class="mt-2 text-xl font-semibold text-gray-800 dark:text-white">
                            {{ row.book_name }}
                        </h3>
                        <p class="text-gray-500">{{ row.author_name }}</p>
                        <p v-if="row.status == 1"
                            class="text-green-400 text-center border border-green-500 bg-green-50 py-1 px-1 rounded-md mt-3 ps-3 flex flex-row-reverse items-center justify-end gap-2 w-full md:w-1/2">
                            <span>Approved</span>
                            <span class="relative flex h-5 w-5 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </p>
                        <p v-else-if="row.status == 2"
                            class="text-orange-400 text-center border border-orange-500 bg-orange-200 py-1 px-1 rounded-md mt-3 ps-3 flex flex-row-reverse items-center justify-end gap-2 w-full md:w-1/2">
                            <span>Pending</span>
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75">
                                </span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                            </span>
                        </p>
                        <p v-else-if="row.status == 0"
                            class="text-red-400 text-center border border-red-500 bg-red-200 py-1 px-1 rounded-md mt-3 ps-3 flex flex-row-reverse items-center justify-end gap-2 w-full md:w-1/2">
                            <span>Rejected</span>
                            <span class="relative flex h-5 w-5 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
