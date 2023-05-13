<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ModalForm from "@/Components/Admin/ModalForm.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

// Retrieve Data from Backend props
defineProps({
    book: Object,
});

const showModal = ref(false);
const path = ref("/uploads/");

const form = useForm({
    userID: null,
    bookID: null,
    status: null,
    to_return: null,
    is_return: null,
});

const addBookRequest = (userID, bookID) => {
    alert(userID + " " + bookID);
};
</script>

<template>
    <Head title="Book Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Book Details
            </h2>
        </template>
        <!-- Books Display -->
        <div class="container px-6 py-10 mx-auto max-w-screen-xl">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center cursor-pointer">
                        <Link :href="route('books')"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        Browse Books
                        </Link>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                                Book Details
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
            <!-- Display Book Details -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 justify-start bg-white p-4 shadow rounded-md">
                <div>
                    <img class="rounded-lg shadow w-full" :src="path + book.book_img" />
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-blue-500 font-normal">{{
                        book.category_name
                    }}</span>
                    <h2 class="text-3xl font-semibold text-gray-800 dark:text-white">
                        {{ book.book_name }}
                    </h2>
                    <p class="text-gray-500 mb-2">{{ book.author_name }}</p>
                    <p class="text-gray-700 mb-4">
                        {{ book.description }}
                    </p>
                    <div class="flex justify-center flex-col mb-4">
                        <p class="text-xl font-bold mr-2">
                            Fee:
                            <span class="font-normal text-lg">₱{{ book.book_fees }}.00</span>
                        </p>
                        <p class="text-xl font-bold mr-2">
                            Qty:
                            <span class="font-normal text-lg">{{ book.quantity }} Copies</span>
                        </p>
                        <p class="text-lg font-bold mr-2" v-if="book.is_active == 1">
                            Status:
                            <span class="text-sm font-normal bg-green-400 py-1 px-3 rounded-lg text-white">Available</span>
                        </p>
                        <p class="text-lg font-bold mr-2" v-if="book.is_active == 0">
                            Status:
                            <span class="text-sm font-normal bg-red-400 py-1 px-3 rounded-lg text-white">Out of stock</span>
                        </p>
                    </div>
                    <div>
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-5 rounded" @click="showModal = true">
                            Borrow Now
                        </button>
                        <!-- Modal-Form -->
                        <ModalForm v-show="showModal">
                            <form @submit.prevent="addBook">
                                <div class="mb-4 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="mb-3">
                                        <label for="to_return" class="block text-gray-500 text-sm font-bold mb-2">When do you want to return the book?</label>
                                        <input type="date"
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            v-model="form.to_return" id="to_return" placeholder="Name of the book" />
                                    </div>
                                </div>
                                <!-- Actions -->
                                <div class="bg-gray-100 px-4 py-2 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto flex flex-row gap-2">
                                        <button type="submit" :disabled="form.processing" @click="addBookRequest(1, 2)"
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
