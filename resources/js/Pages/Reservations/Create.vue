<template>
    <AppLayout title="Crear reserva">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Reserva
            </h2>
            <ReturnLink :action="route('reservations.index')" />
        </template>
        <Form :form="form" method="post" :url="route('reservations.store')" />
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Form from "@/Pages/Reservations/Form.vue";
import ReturnLink from "@/Rentacar/Components/ReturnLink.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    item: Object,
});

const form = useForm(props.item);
</script>

<style lang="scss" scoped></style>
