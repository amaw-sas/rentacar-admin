<template>
    <Link :href="action" method="delete" as="button">
        <VueFeather type="trash" />
    </Link>
</template>

<script setup>
import VueFeather from "vue-feather";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    action: String,
});
</script>

<style lang="scss" scoped></style>
