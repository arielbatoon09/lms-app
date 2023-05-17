<script setup>
import { ref } from 'vue';
import Sidebar from '@/Components/Admin/Sidebar.vue';
import Navmenu from '@/Components/Admin/Navmenu.vue';

const sidebarOpen = ref(true);


</script>

<template>
    <div class="w-screen flex fixed h-full">
        <!-- Sidebar -->
        <div class="w-[350px] bg-gray-200" v-if="sidebarOpen">
            <Sidebar/>
        </div>
        <div class="w-full h-full shadow overflow-y-auto">
            <!-- Navbar -->
            <div class="h-[70px] bg-white flex justify-between items-center mx-5">
                <div class="cursor-pointer" @click="sidebarOpen = !sidebarOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path fill-rule="evenodd" d="M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                    </svg>
                </div>
                <Navmenu/>
            </div>
            <!-- Page-Content -->
            <main class="w-full h-[700px] bg-gray-50">
                <slot/>
            </main>
        </div>
    </div>
</template>