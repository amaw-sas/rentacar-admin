<template>
    <div
        class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700"
    >
        <div class="flex items-center justify-between mb-4">
            <h5
                class="text-base font-bold leading-none text-gray-900 dark:text-white"
            >
                <slot name="title"></slot>
            </h5>
        </div>
        <div class="flow-root">
            <slot></slot>
        </div>
    </div>
</template>
