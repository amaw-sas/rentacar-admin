<template>
    <Link
        id="cancel-button"
        as="button"
        type="button"
        class="bg-red-300 hover:bg-red-400 text-red-800 font-bold py-2 px-4 rounded inline-flex items-center"
        :href="action"
        alt="Cancelar"
    >
        <VueFeather type="x" />
        <span class="ml-2">Cancelar</span>
    </Link>
</template>

<script setup>
import VueFeather from "vue-feather";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    action: String,
});
</script>
