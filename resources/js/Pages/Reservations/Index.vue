<template>
  <AppLayout title="Reservas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reservas</h2>
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
              @filterData="(data) => filterData(route('reservations.index'), data)"
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
                <RentacarFilterDateTimeRange field="created_at" placeholder="Creación" />
                <RentacarFilterDateRange field="pickup_date" placeholder="Recogida" />
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
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <DataTable :headers="paginator.data.headers" :items="paginator.data.items">
                <template #header-created_at="header">
                    <RentacarSortableCol database_col="created_at" >
                        CREADO
                    </RentacarSortableCol>
                </template>
                <template #header-pickup_date="header">
                    <RentacarSortableCol database_col="pickup_date" >
                        DÍA RECOGIDA
                    </RentacarSortableCol>
                </template>
              <template #expand="item">
                <Expand :item="item" />
              </template>
              <template #item-created_at="{ created_at }">
                <span class="short-text" :title="created_at" v-text="created_at"></span>
              </template>
              <template #item-fullname="{ fullname }">
                <span class="short-text" :title="fullname" v-text="fullname"></span>
              </template>
              <template #item-phone="{ phone, whatsapp_link }">
                <a
                  target="_blank"
                  rel="noopener noreferrer"
                  class="text-blue-500 hover:text-blue-800 cursor-pointer short-text"
                  :href="whatsapp_link"
                  :title="phone"
                  v-text="phone"
                ></a>
              </template>
              <template #item-email="{ email }">
                <a
                  class="text-blue-500 hover:text-blue-800 cursor-pointer short-text"
                  :href="'mailto:' + email"
                  :title="email"
                  v-text="email"
                ></a>
              </template>
              <template
                #item-operation="{
                  edit_url,
                  email_preview_url,
                  reserve_code,
                  total_price,
                }"
              >
                <div class="operation-wrapper">
                  <div style="padding: 15px" class="flex flex-row gap-2">
                    <EditItemButton :action="edit_url" />
                    <PreviewClientMailButton
                      v-if="reserve_code && total_price"
                      :action="email_preview_url"
                    />
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
import PreviewClientMailButton from "@/Rentacar/Components/Buttons/PreviewClientMailButton.vue";
import RentacarFilterDateRange from "@/Rentacar/Components/Filters/FilterDateRange.vue";
import RentacarFilterDateTimeRange from "@/Rentacar/Components/Filters/FilterDateTimeRange.vue";
import RentacarFilterEnumerable from "@/Rentacar/Components/Filters/FilterEnumerable.vue";
import RentacarFilterToolbar from "@/Rentacar/Components/FilterToolbar.vue";
import RentacarList from "@/Rentacar/Components/List.vue";
import RentacarSortableCol from "@/Rentacar/Components/SortableCol.vue";
import RentacarSimplePaginator from "@/Rentacar/Components/SimplePaginator.vue";

import { filterData } from "@/Rentacar/Functions/table.js";

import DataTable from "@/Rentacar/Components/DataTable.vue";

import { computed, provide } from "vue";
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

provide('indexRoute', route('reservations.index'));
provide('orderByCol', usePage().props.paginator.meta.orderByCol);
provide('orderOrientation', usePage().props.paginator.meta.orderOrientation);
provide('filterStartDate', usePage().props.paginator.meta.filterStartDate);
provide('filterEndDate', usePage().props.paginator.meta.filterEndDate);
provide('filterCols', usePage().props.paginator.meta.filterCols);
provide('query', usePage().props.paginator.meta.query);
</script>
