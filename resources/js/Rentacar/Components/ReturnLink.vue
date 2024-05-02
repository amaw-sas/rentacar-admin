<template>
    <Link
        id="return-link"
        class="text-blue-800 font-bold py-2 px-4"
        :href="action"
        alt="Retornar"
    >
        <VueFeather type="corner-up-left" size="14" />
        <span class="ml-2">O retornar a listado</span>
    </Link>
</template>

<script setup>
import VueFeather from "vue-feather";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    action: String,
});
</script>
