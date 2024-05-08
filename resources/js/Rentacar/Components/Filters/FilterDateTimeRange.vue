<template>
    <div class="w-auto">
        <Datepicker
            id="filter-datetime-range"
            v-model="datetime_range"
            format="dd-MM-yyyy HH:mm"
            modelType="yyyy-MM-dd HH:mm"
            locale="es"
            autoApply
            class="block mt-1 w-[450px]"
            autofocus
            placeholder="Filtrar por rango de fecha"
            :textInput="true"
            :enableTimePicker="true"
            :multiCalendars="true"
            :range="true"
            :presetRanges="presetRanges"
            :textInputOptions="dateTextInputOptions"
            :startTime="startTime"
        />
        <input
            v-if="filterStartDate"
            type="hidden"
            name="filterStartDate"
            :value="filterStartDate"
        />
        <input
            v-if="filterEndDate"
            type="hidden"
            name="filterEndDate"
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

const previousStartDate = usePage().props?.elements?.meta?.filterStartDate;
const previousEndDate = usePage().props?.elements?.meta?.filterEndDate;

let datetime_range = ref([previousStartDate, previousEndDate]);

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

let filterStartDate = ref(previousStartDate);
let filterEndDate = ref(previousEndDate);

const dateTextInputOptions = {
    rangeSeparator: "~",
};

watch(datetime_range, (newDateRange) => {
    filterStartDate.value = newDateRange?.[0];
    filterEndDate.value = newDateRange?.[1];
});
</script>
