<template>
    <span>
        <!-- progress -->
        <progress
            v-if="form.progress"
            :value="form.progress.percentage"
            max="100"
        >
            {{ form.progress.percentage }}%
        </progress>
        <!-- submit -->
        <PrimaryButton type="submit">
            <VueFeather type="save"></VueFeather>
            <span class="ml-2">Guardar</span>
        </PrimaryButton>
    </span>
</template>

<script setup>
import VueFeather from "vue-feather";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    form: Object,
});
</script>

<style lang="scss" scoped></style>
