<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: [String, Number],
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        type="text"
        class="input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm h-[42px] text-[0.85rem] text-gray-500 placeholder:text-gray-500"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
