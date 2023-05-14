<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Retrieve Data from Backend props
defineProps({
    books: Object,
});

const path = ref('/uploads/');

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
        <div class="container px-6 py-10 mx-auto max-w-screen-xl">
            <div class="grid grid-cols-1 gap-4 md:gap-8 md:grid-cols-2 xl:grid-cols-3">
                <div class="bg-white shadow px-5 py-6 rounded-lg cursor-pointer relative" v-for="row in books" :key="row.id"
                    @click="goBookDetails(row.id, $page.props.auth.user.id)">
                    <span class="absolute bg-blue-100 top-3 px-2 text-blue-600 rounded-lg">Tap to see details</span>
                    <img class="object-cover object-center w-full h-64 rounded-lg lg:h-70" :src="path + row.book_img" />
                    <div class="mt-8">
                        <!-- Book Category -->
                        <span class="text-blue-500 font-normal">{{ row.category != 0 ? row.category : Unknown }}</span>
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
