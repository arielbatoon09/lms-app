<template>
    <div class="w-100 bg-white rounded shadow border-l-4 border-blue-500 hover:border-blue-400 cursor-pointer">
        <slot name="card-header"/>
        <div class="p-4">
            <h2 class="text-xl font-semibold"><slot name="card-title"/></h2>
            <p class="text-gray-700 mt-2"><slot name="card-description"/></p>
            <div class="flex items-center mt-4">
                <slot name="card-footer"/>
            </div>
        </div>
    </div>
</template>