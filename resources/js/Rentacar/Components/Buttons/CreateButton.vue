<template>
    <Link
        id="create-button"
        as="button"
        type="button"
        class="bg-blue-300 hover:bg-blue-400 text-blue-800 font-bold py-2 px-4 rounded inline-flex items-center"
        :href="action"
        alt="Crear"
    >
        <VueFeather type="plus" />
        <span class="ml-2">Crear</span>
    </Link>
</template>

<script setup>
import VueFeather from "vue-feather";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    action: String,
});
</script>
