<template>
    <div class="toolbar">
        <div
            class="flex flex-auto flex-col space-y-2 md:space-y-0 md:flex-row md:justify-between"
        >
            <div
                v-if="hasActions"
                id="table-actions"
                dusk="table-actions"
                class="md:w-1/4 justify-items-start"
            >
                <slot name="actions"></slot>
            </div>
            <div
                id="table-filters"
                dusk="table-filters"
                class="flex flex-row justify-items-end md:w-full"
            >
                <slot name="filters"></slot>
            </div>
        </div>
    </div>

    <div v-if="hasElements" class="mytable">
        <slot></slot>
    </div>

    <div v-else>
        <p class="my-3">Nada que mostrar</p>
    </div>

    <div id="paginator" dusk="paginator">
        <slot name="pagination"></slot>
    </div>
</template>

<script setup>
import { computed, useSlots } from "vue";

const slots = useSlots();

const hasActions = computed(() => !!slots.actions);

const hasElements = computed(() => !!slots.default);
</script>
