<template>
    <div class="w-auto">
        <select :id="componentId" v-model="selected">
            <option :value="null" v-text="nullText"></option>
            <option
                v-for="option in options"
                :key="componentId + '-' + option.value"
                :value="option.value"
                v-text="option.text"
            ></option>
        </select>
        <input
            v-if="selected"
            type="hidden"
            :name="`filterCols[${field}][value]`"
            :value="selected"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    field: {
        required: true,
        type: String,
    },
    options: {
        required: true,
        type: Array,
    },
    nullText: {
        required: false,
        type: String,
        default: "Seleccione una opción",
    },
});

const componentId = `filter-col-${props.field}`;
const selected = ref(
    usePage().props.paginator.meta?.filterCols?.[props.field]?.value ?? null
);
</script>

<style lang="scss" scoped></style>
