<template>
    <AppLayout title="Reservas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reservas
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="mb-2">
                    <CreateButton
                        :action="route('reservations.create')"
                    ></CreateButton>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Vue3EasyDataTable
                        v-model:items-selected="itemsSelected"
                        :headers="headers"
                        :items="items"
                    >
                        <template #expand="item">
                            <Expand :item="item" />
                        </template>
                        <template #item-phone="{ phone, whatsapp_link }">
                            <a
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-blue-500 hover:text-blue-800 cursor-pointer"
                                :href="whatsapp_link"
                                v-text="phone"
                            ></a>
                        </template>
                        <template #item-email="{ email }">
                            <a
                                class="text-blue-500 hover:text-blue-800 cursor-pointer"
                                :href="'mailto:' + email"
                                v-text="email"
                            ></a>
                        </template>
                        <template #item-operation="{ edit_url }">
                            <div class="operation-wrapper">
                                <div style="padding: 15px">
                                    <EditItemButton :action="edit_url" />
                                </div>
                            </div>
                        </template>
                    </Vue3EasyDataTable>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CreateButton from "@/Components/Rentacar/CreateButton.vue";
import EditItemButton from "@/Components/Rentacar/EditItemButton.vue";
import Expand from "@/Pages/Reservations/Expand.vue";
import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
import { ref } from "vue";

const props = defineProps({
    headers: Array,
    items: Array,
});

const itemsSelected = ref([]);
</script>

<style lang="scss" scoped></style>
