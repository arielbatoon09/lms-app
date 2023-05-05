<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Browse Books"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browse Books</h2>
        </template>

        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-1">
                <div class="overflow-hidden p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                        <Card>
                            <template #card-header>
                                This is for Image
                            </template>
                            <template #card-title>
                                This is for Title
                            </template>
                            <template #card-description>
                                This is for Description
                            </template>
                            <template #card-footer>
                                This is for Footer
                            </template>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
