<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'flex items-center py-3 px-4 gap-3 text-gray-400 cursor-pointer bg-gray-700 border-l-2 border-blue-500 hover:bg-gray-700 text-gray-100 transition duration-150 ease-in-out'
        : 'flex items-center py-3 px-4 gap-3 text-gray-400 cursor-pointer hover:text-gray-100 transition duration-150 ease-in-out'
)
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
