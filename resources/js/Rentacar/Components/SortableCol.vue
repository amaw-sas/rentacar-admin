<template>
    <div ref="sortableCol">
        <a
            :id="orderableColId"
            :dusk="orderableColId"
            :href="indexRoute"
            preserve-scroll
            class="text-blue-500 flex flex-row items-center"
            @click.prevent="toggleCol"
        >
            <svg v-if="order_desc === true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="12" height="12">
                <title>descendente</title>
                <path d="M24 12L16 22 8 12z"/>
                <path fill="none" d="M0 0H32V32H0z"/>
            </svg>
            <svg v-if="order_desc === false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="12" height="12">
                <title>ascendente</title>
                <path d="M8 20L16 10 24 20z"/>
                <path fill="none" d="M0 0H32V32H0z"/>
            </svg>
            <svg v-if="order_desc === null" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="12" height="12">
                <title>normal</title>
                <path d="M12 8L22 16 12 24z"/>
                <path fill="none" d="M0 0H32V32H0z"/>
            </svg>
            <span>
                <slot></slot>
            </span>
        </a>
    </div>
</template>

<script setup>

import { router } from '@inertiajs/vue3'
import { ref, inject } from 'vue'

const props = defineProps({
    database_col: {
        type: String,
        required: true
    },
    width: {
        type: String,
        default: 'w-1/5',
        required: false
    },
    only_data: {
        type: Array,
        default: ['elements'],
        required: false
    }
});

const orderableColId = 'orderable-column-' + props.database_col.replace('.','-')
const indexRoute = inject('indexRoute');
const orderByCol = inject('orderByCol');
const orderOrientation = inject('orderOrientation');
const filterStartDate = inject('filterStartDate');
const filterEndDate = inject('filterEndDate');
const filterCols = inject('filterCols');
const query = inject('query');

let order_desc = ref((orderByCol == props.database_col) ? (orderOrientation == 'desc' ? true : false) : null);
let order_orientation = (order_desc.value) ? 'desc' : 'asc'

function toggleCol(){

    order_desc.value = !order_desc.value;
    order_orientation = (order_desc.value) ? 'desc' : 'asc'

    router.get(
        indexRoute,
        {
            orderByCol: props.database_col,
            orderOrientation: order_orientation,
            filterStartDate,
            filterEndDate,
            filterCols,
            query,
        },
        {
            preserveScroll: true,
            // only: props.only_data
        }
    )
}


</script>

<style lang="scss" scoped>

</style>
