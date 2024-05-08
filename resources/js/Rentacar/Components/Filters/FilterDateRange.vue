<template>
    <div class="w-auto">
        <VueDatepicker
            :id="`filter-date-range-${field}`"
            v-model="dateRange"
            format="dd-MM-yyyy"
            :model-type="format"
            locale="es"
            auto-apply
            class="block mt-1 w-[450px]"
            autofocus
            :range="true"
            :placeholder="placeholder"
            :enableTimePicker="false"
            :multiCalendars="true"
            :preset-dates="presetRanges"
            :textInputOptions="dateTextInputOptions"
            :timezone="{ tz: 'America/New_York', offset: -5 }"
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
import VueDatepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import {
    add,
    format as formatDate,
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
        default: "yyyy-MM-dd",
    },
    placeholder: {
        required: false,
        type: String,
        default: "Filtrar por rango de fecha",
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
        label: "Esta semana",
        value: [startOfWeek(new Date()), endOfWeek(new Date())],
    },
    {
        label: "Este mes",
        value: [startOfMonth(new Date()), endOfMonth(new Date())],
    },
    {
        label: "Mes pasado",
        value: [
            startOfMonth(subMonths(new Date(), 1)),
            endOfMonth(subMonths(new Date(), 1)),
        ],
    },
    {
        label: "Este año",
        value: [startOfYear(new Date()), endOfYear(new Date())],
    },
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
