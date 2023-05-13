<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Retrieve Data from Backend props
defineProps({
    books: Object,
});

const path = ref('/uploads/');

const test =(id)=> {
    alert(id);
}
</script>

<template>
    <Head title="Book Issued" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Book Issued</h2>
        </template>
        <!-- Books Display -->
        <div class="container px-6 py-10 mx-auto max-w-screen-xl">
            <div class="grid grid-cols-1 gap-4 md:gap-8 md:grid-cols-2 xl:grid-cols-3">
                <div class="bg-white shadow px-5 py-6 rounded-lg cursor-pointer relative"
                    @click="test(1)">
                    <span class="absolute bg-blue-100 top-3 px-2 text-blue-600 rounded-lg">Tap to see details</span>
                    <img class="object-cover object-center w-full h-64 rounded-lg lg:h-70" src="../../images/448204-Himself.jpg" />
                    <div class="mt-8">
                        <!-- Book Category -->
                        <span class="text-blue-500 font-normal">Category</span>
                        <!-- Book Title -->
                        <h3 class="mt-2 text-xl font-semibold text-gray-800 dark:text-white">
                            Book Name
                        </h3>
                        <p class="text-gray-500">Author Name</p>
                        <p class="mt-2 text-gray-500 dark:text-gray-400 truncate">
                            <span class="text-gray-500 font-semibold">Book Description<br></span>
                            A new description
                        </p>
                        <p class="text-white bg-green-400 py-1 px-1 rounded-md">Accepted</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
