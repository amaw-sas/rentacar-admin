<template>
    <div class="flex-1 min-w-0">
        <p
            class="text-xs font-medium truncate text-gray-900"
            v-text="label"
        ></p>
    </div>
    <div
        class="text-xs inline-flex items-center text-gray-900"
        v-text="value"
    ></div>
</template>

<script setup>
const props = defineProps({
    label: {
        required: true,
        type: String,
    },
    value: {
        required: true,
        type: [String, Number],
    },
});
</script>
