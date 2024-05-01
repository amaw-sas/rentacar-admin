<template>
    <Vue3EasyDataTable
        v-model:items-selected="itemsSelected"
        :headers="headers"
        :items="items"
    >
        <template #expand="item">
            <slot name="expand" v-bind="item"></slot>
        </template>
        <template #item-operation="item">
            <div class="operation-wrapper">
                <div style="padding: 15px">
                    <EditItemButton :action="item?.edit_url" />
                    <slot name="more-operations"></slot>
                </div>
            </div>
        </template>
    </Vue3EasyDataTable>
</template>

<script setup>
import { ref } from "vue";
import EditItemButton from "@/Rentacar/Components/EditItemButton.vue";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";

const props = defineProps({
    headers: Array,
    items: Array,
});

const itemsSelected = ref([]);
</script>

<style lang="scss" scoped></style>
