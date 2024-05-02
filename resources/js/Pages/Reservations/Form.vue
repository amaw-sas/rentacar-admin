<template>
    <FormSection @submitted="formSubmit(form, method, url)">
        <template #form>
            <InputFormField field="fullname" name="Nombre" :form="form" />

            <SelectFormField
                field="identification_type"
                name="Tipo identificación"
                :form="form"
                :options="identificationTypesOptions"
            />

            <InputFormField
                field="identification"
                name="Identificación"
                :form="form"
            />
            <InputFormField field="phone" name="Teléfono" :form="form" />
            <InputFormField field="email" name="Email" :form="form" />

            <SelectFormField
                field="category"
                name="Categoría"
                :form="form"
                :options="categoryOptions"
            />

            <SelectFormField
                field="pickup_location"
                name="Lugar recogida"
                :form="form"
                :options="branchesOptions"
            />
            <SelectFormField
                field="return_location"
                name="Lugar retorno"
                :form="form"
                :options="branchesOptions"
            />
            <div class="col-span-6 sm:col-span-4">
                <div class="flex flex-col md:flex-row space-x-2 space-y-2">
                    <DateFormField
                        field="pickup_date"
                        name="Día recogida"
                        :form="form"
                    />
                    <HourFormField
                        field="pickup_hour"
                        name="Hora recogida"
                        :form="form"
                    />
                </div>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <div class="flex flex-col md:flex-row space-x-2 space-y-2">
                    <DateFormField
                        field="return_date"
                        name="Día retorno"
                        :form="form"
                    />
                    <HourFormField
                        field="return_hour"
                        name="Hora retorno"
                        :form="form"
                    />
                </div>
            </div>

            <InputFormField
                field="selected_days"
                name="Días reservados"
                :form="form"
            />
            <InputFormField
                v-if="method == 'post'"
                field="extra_hours"
                name="Horas extras"
                :form="form"
            />
            <MoneyInputFormField
                v-if="method == 'post'"
                field="extra_hours_price"
                name="Precio horas extras"
                :form="form"
            />
            <InputFormField
                v-if="method == 'post'"
                field="coverage_days"
                name="Días seguro"
                :form="form"
            />
            <MoneyInputFormField
                v-if="method == 'post'"
                field="coverage_price"
                name="Precio seguro"
                :form="form"
            />
            <MoneyInputFormField
                v-if="method == 'post'"
                field="iva_fee"
                name="IVA"
                :form="form"
            />
            <MoneyInputFormField
                v-if="method == 'post'"
                field="tax_fee"
                name="Tasa administrativa"
                :form="form"
            />
            <MoneyInputFormField
                field="total_price"
                name="Precio total"
                :form="form"
            />
            <MoneyInputFormField
                field="total_price_localiza"
                name="Precio total Localiza"
                :form="form"
            />

            <SelectFormField
                field="franchise"
                name="Franquicia"
                :form="form"
                :options="franchisesOptions"
            />

            <InputFormField
                field="reserve_code"
                name="Código de reserva"
                :form="form"
            />

            <InputFormField field="user" name="Referido" :form="form" />

            <SelectFormField
                field="status"
                name="Estado"
                :form="form"
                :options="reservationStatusOptions"
            />
        </template>

        <template #actions>
            <div class="grid grid-cols-2 gap-4 place-content-between h-48">
                <div class="text-start">
                    <DeleteButton
                        v-if="form.id"
                        :action="route('reservations.destroy', form.id)"
                    ></DeleteButton>
                </div>
                <div class="space-x-2">
                    <SubmitButton :form="form"></SubmitButton>
                    <CancelButton :action="route('reservations.index')" />
                </div>
            </div>
        </template>
    </FormSection>
</template>

<script setup>
import InputFormField from "@/Rentacar/Components/InputFormField.vue";
import SelectFormField from "@/Rentacar/Components/SelectFormField.vue";
import ReadOnlyFormField from "@/Rentacar/Components/ReadOnlyFormField.vue";
import DateFormField from "@/Rentacar/Components/DateFormField.vue";
import MoneyInputFormField from "@/Rentacar/Components/MoneyInputFormField.vue";
import HourFormField from "@/Rentacar/Components/HourFormField.vue";
import CancelButton from "@/Rentacar/Components/CancelButton.vue";
import DeleteButton from "@/Rentacar/Components/DeleteButton.vue";
import SubmitButton from "@/Rentacar/Components/SubmitButton.vue";
import FormSection from "@/Components/FormSection.vue";

import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { formSubmit } from "@/Rentacar/Functions/form";

defineProps({
    form: Object,
    method: String,
    url: String,
});

const categoryOptions = computed(() =>
    usePage().props.categories.map((category) => ({
        value: category.id,
        text: category.name,
    }))
);

const branchesOptions = computed(() =>
    usePage().props.branches.map((branch) => ({
        value: branch.id,
        text: branch.name,
    }))
);

const franchisesOptions = computed(() =>
    usePage().props.franchises.map((franchise) => ({
        value: franchise.id,
        text: franchise.name,
    }))
);

const identificationTypesOptions = computed(() =>
    usePage().props.identification_types.map((identificationType) => ({
        value: identificationType.value,
        text: identificationType.value,
    }))
);

const reservationStatusOptions = computed(() =>
    usePage().props.reservation_status.map((identificationType) => ({
        value: identificationType.value,
        text: identificationType.value,
    }))
);
</script>

<style lang="scss" scoped></style>
