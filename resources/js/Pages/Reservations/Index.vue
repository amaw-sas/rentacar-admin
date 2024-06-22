<template>
    <AppLayout title="Reservas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reservas
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <RentacarList>
                    <template #actions>
                        <CreateButton :action="route('reservations.create')" />
                    </template>
                    <template #filters>
                        <RentacarFilterToolbar
                            :clean-url="route('reservations.cleanFilters')"
                            @filterData="
                                (data) =>
                                    filterData(
                                        route('reservations.index'),
                                        data
                                    )
                            "
                        >
                            <template #custom-filters>
                                <RentacarFilterEnumerable
                                    field="franchise"
                                    :options="franchisesOptions"
                                    null-text="Franquicia"
                                />
                                <RentacarFilterEnumerable
                                    field="status"
                                    :options="reservationStatusOptions"
                                    null-text="Estado"
                                />
                                <RentacarFilterDateRange
                                    field="created_At"
                                    placeholder="Creación"
                                />
                                <RentacarFilterDateRange
                                    field="pickup_date"
                                    placeholder="Recogida"
                                />
                            </template>
                        </RentacarFilterToolbar>
                    </template>
                    <template #pagination>
                        <RentacarSimplePaginator
                            v-if="paginator.meta.total > 0"
                            :links="paginator.links"
                            :meta="paginator.meta"
                        />
                    </template>

                    <div class="mb-2"></div>
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg"
                    >
                        <DataTable
                            :headers="paginator.data.headers"
                            :items="paginator.data.items"
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
                        </DataTable>
                    </div>
                </RentacarList>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Expand from "@/Pages/Reservations/Expand.vue";
import CreateButton from "@/Rentacar/Components/Buttons/CreateButton.vue";
import EditItemButton from "@/Rentacar/Components/Buttons/EditItemButton.vue";
import RentacarFilterDateRange from "@/Rentacar/Components/Filters/FilterDateRange.vue";
import RentacarFilterEnumerable from "@/Rentacar/Components/Filters/FilterEnumerable.vue";
import RentacarFilterToolbar from "@/Rentacar/Components/FilterToolbar.vue";
import RentacarList from "@/Rentacar/Components/List.vue";
import RentacarSimplePaginator from "@/Rentacar/Components/SimplePaginator.vue";

import { filterData } from "@/Rentacar/Functions/table.js";

import DataTable from "@/Rentacar/Components/DataTable.vue";

import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    paginator: Object,
});

const reservationStatusOptions = computed(() =>
    usePage().props.reservation_status.map((identificationType) => ({
        value: identificationType.value,
        text: identificationType.text,
    }))
);

const franchisesOptions = computed(() =>
    usePage().props.franchises.map((franchise) => ({
        value: franchise.id,
        text: franchise.name,
    }))
);
</script>

<style lang="scss" scoped></style>
