<template>
    <div class="w-auto">
        <Datepicker
            :id="`filter-datetime-range-${field}`"
            v-model="dateRange"
            format="dd-MM-yyyy HH:mm"
            :modelType="format"
            locale="es"
            autoApply
            class="block w-[160px] h-[42px]"
            autofocus
            :range="true"
            :placeholder="placeholder"
            :enableTimePicker="true"
            :textInput="true"
            :multiCalendars="true"
            :presetRanges="presetRanges"
            :textInputOptions="dateTextInputOptions"
            :startTime="startTime"
        />
        <input
            v-if="filterStartDate"
            type="hidden"
            :name="`filterDateRanges[${field}][start]`"
            :value="filterStartDate"
        />
        <input
            v-if="filterEndDate"
            type="hidden"
            :name="`filterDateRanges[${field}][end]`"
            :value="filterEndDate"
        />
    </div>
</template>

<script setup>
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import {
    endOfMonth,
    endOfYear,
    startOfMonth,
    startOfYear,
    startOfWeek,
    endOfWeek,
    subMonths,
} from "date-fns";



const props = defineProps({
    field: {
        required: true,
        type: String,
    },
    format: {
        required: false,
        type: String,
        default: "yyyy-MM-dd HH:mm",
    },
    placeholder: {
        required: false,
        type: String,
        default: "Filtrar por rango de fecha hora",
    },
    defaultStartDate: {
        required: false,
        type: String,
    },
    defaultEndDate: {
        required: false,
        type: String,
    },
});

const defaultStartDate =
    usePage().props.paginator.meta?.filterDateRanges?.[props.field]?.start ??
    "";

const defaultEndDate =
    usePage().props.paginator.meta?.filterDateRanges?.[props.field]?.end ?? "";


let dateRange = ref([defaultStartDate, defaultEndDate]);

const presetRanges = ref([
    {
        label: "Hoy",
        range: [new Date(), new Date()],
    },
    {
        label: "Esta semana",
        range: [startOfWeek(new Date()), endOfWeek(new Date())],
    },
    {
        label: "Este mes",
        range: [startOfMonth(new Date()), endOfMonth(new Date())],
    },
    {
        label: "Mes pasado",
        range: [
            startOfMonth(subMonths(new Date(), 1)),
            endOfMonth(subMonths(new Date(), 1)),
        ],
    },
    {
        label: "Este año",
        range: [startOfYear(new Date()), endOfYear(new Date())],
    },
]);

const startTime = ref([
    { hours: 0, minutes: 0 },
    { hours: 23, minutes: 59 },
]);

let filterStartDate = ref(defaultStartDate);
let filterEndDate = ref(defaultEndDate);

const dateTextInputOptions = {
    rangeSeparator: "~",
};

watch(dateRange, (newDateRange) => {
    filterStartDate.value = newDateRange?.[0];
    filterEndDate.value = newDateRange?.[1];
});
</script>
