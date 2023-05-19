<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// Retrieve Data from Backend props
defineProps({
    allBooksCount: Number,
    totalActiveBooks: Number,
    inactiveBookCount: Number,
    pendingBookRequestCount: Number,
    approvedBookRequestCount: Number,
    rejectedBookRequestCount: Number,
    unpaidInvoiceCount: Number,
    paidInvoiceCount: Number,
    otcInvoiceCount: Number,
    returnedBooks: Number,
    countByIsReturn: Number,
});

</script>

<template>
    <Head :title="$page.props.auth.user.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:h-screen">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10 px-4">
                    <Link :href="route('book-request')">
                    <div
                        class="bg-white px-4 py-5 h-[200px] border-l-4 border-blue-500 shadow relative hover:border-blue-400">
                        <h2 class="text-xl font-semibold text-gray-600 mb-3 flex gap-2 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span>Books</span>
                        </h2>
                        <p class="text-orange-500 mb-1">
                            Total Books: <span class="text-gray-600">{{ allBooksCount }}</span>
                        </p>
                        <p class="text-green-500 mb-1">
                            Available: <span class="text-gray-600">{{ totalActiveBooks }}</span>
                        </p>
                        <p class="text-red-500">
                            Inactive: <span class="text-gray-600">{{ inactiveBookCount }}</span>
                        </p>
                    </div>
                    </Link>
                    <Link :href="route('book-request')">
                    <div
                        class="bg-white px-4 py-5 h-[200px] border-l-4 border-purple-500 shadow relative hover:border-purple-400">
                        <h2 class="text-xl font-semibold text-gray-600 mb-3 flex gap-2 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                            </svg>
                            <span>Book Requests</span>
                        </h2>
                        <p class="text-orange-500 mb-1">
                            Pending: <span class="text-gray-600">{{ pendingBookRequestCount }}</span>
                        </p>
                        <p class="text-green-500 mb-1">
                            Approved: <span class="text-gray-600">{{ approvedBookRequestCount }}</span>
                        </p>
                        <p class="text-red-500">
                            Rejected: <span class="text-gray-600">{{ rejectedBookRequestCount }}</span>
                        </p>
                    </div>
                    </Link>
                    <Link :href="route('books-issued')">
                    <div
                        class="bg-white px-4 py-5 h-[200px] border-l-4 border-green-500 shadow relative hover:border-green-400">
                        <h2 class="text-xl font-semibold text-gray-600 mb-3 flex gap-2 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                            </svg>
                            <span>Issued Books</span>
                        </h2>
                        <p class="text-orange-500 mb-1">
                            On-going: <span class="text-gray-600">123</span>
                        </p>
                        <p class="text-green-500 mb-1">
                            Returned: <span class="text-gray-600">{{ returnedBooks }}</span>
                        </p>
                        <p class="text-red-500">
                            Overdue: <span class="text-gray-600">{{ countByIsReturn }}</span>
                        </p>
                    </div>
                    </Link>
                    <Link :href="route('invoices.user')">
                    <div
                        class="bg-white px-4 py-5 h-[200px] border-l-4 border-yellow-500 shadow relative hover:border-yellow-400">
                        <h2 class="text-xl font-semibold text-gray-600 mb-3 flex gap-2 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Invoices</span>
                        </h2>
                        <p class="text-orange-500 mb-1">
                            Over-The-Counter <span class="text-gray-600">{{ otcInvoiceCount }}</span>
                        </p>
                        <p class="text-green-500 mb-1">
                            Paid: <span class="text-gray-600">{{ paidInvoiceCount }}</span>
                        </p>
                        <p class="text-red-500">
                            Unpaid: <span class="text-gray-600">{{ unpaidInvoiceCount }}</span>
                        </p>
                    </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
