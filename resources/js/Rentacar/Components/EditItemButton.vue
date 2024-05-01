<template>
    <Link :href="action" method="get" as="button">
        <VueFeather type="edit" />
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
