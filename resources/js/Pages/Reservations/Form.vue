<template>
  <FormSection @submitted="formSubmit(form, method, url)">
    <template #form>
      <FormField>
        <div class="grid grid-cols-3 gap-2">
          <div class="flex flex-col">
            <InputFormField field="fullname" name="Nombre" :form="form" />
          </div>
          <div class="flex flex-col">
            <SelectFormField
              field="identification_type"
              name="Tipo identificación"
              :form="form"
              :options="identificationTypesOptions"
            />
          </div>
          <div class="flex flex-col">
            <InputFormField field="identification" name="Identificación" :form="form" />
          </div>
        </div>
      </FormField>
      <FormField>
        <div class="grid grid-cols-2 gap-2">
          <div class="flex flex-col">
            <InputFormField field="phone" name="Teléfono" :form="form" />
          </div>
          <div class="flex flex-col">
            <InputFormField field="email" name="Email" :form="form" />
          </div>
        </div>
      </FormField>

      <FormField>
        <SelectFormField
          field="category"
          name="Categoría"
          :form="form"
          :options="categoryOptions"
        />
      </FormField>

      <FormField>
        <div class="grid grid-cols-3 gap-2">
          <div class="flex flex-col">
            <SelectFormField
              field="pickup_location"
              name="Lugar recogida"
              :form="form"
              :options="branchesOptions"
            />
          </div>
          <div class="flex flex-col">
            <DateFormField field="pickup_date" name="Día recogida" :form="form" />
          </div>
          <div class="flex flex-col">
            <HourFormField field="pickup_hour" name="Hora recogida" :form="form" />
          </div>
        </div>
      </FormField>

      <FormField>
        <div class="grid grid-cols-3 gap-2">
          <div class="flex flex-col">
            <SelectFormField
              field="return_location"
              name="Lugar retorno"
              :form="form"
              :options="branchesOptions"
            />
          </div>
          <div class="flex flex-col">
            <DateFormField field="return_date" name="Día retorno" :form="form" />
          </div>
          <div class="flex flex-col">
            <HourFormField field="return_hour" name="Hora retorno" :form="form" />
          </div>
        </div>
      </FormField>

      <FormField>
        <div class="grid grid-cols-3 gap-2">
          <div class="flex flex-col">
            <NumberInputFormField
              field="selected_days"
              name="Días reservados"
              :form="form"
            />
          </div>
          <div class="flex flex-col">
            <NumberInputFormField
              v-if="method == 'post'"
              field="coverage_days"
              name="Días seguro"
              :form="form"
            />
          </div>
          <div class="flex flex-col">
            <NumberInputFormField
              v-if="method == 'post'"
              field="extra_hours"
              name="Horas extras"
              :form="form"
            />
          </div>
        </div>
      </FormField>

      <FormField>
        <div
          :class="{
            grid: true,
            'grid-cols-4': method == 'post',
            'grid-cols-3': method != 'post',
            'gap-2': true,
            'ms-4': true,
          }"
        >
          <div class="flex flex-col" v-if="method == 'post'">
            <MoneyInputFormField
              field="extra_hours_price"
              name="Precio horas extras"
              :form="form"
            />
          </div>
          <div class="flex flex-col" v-if="method == 'post'">
            <MoneyInputFormField
              field="coverage_price"
              name="Precio seguro"
              :form="form"
            />
          </div>
          <div class="flex flex-col" v-if="method == 'post'">
            <MoneyInputFormField field="return_fee" name="Tarifa retorno" :form="form" />
          </div>
          <div class="flex flex-col" v-if="method == 'post'">
            <MoneyInputFormField field="iva_fee" name="IVA" :form="form" />
          </div>
          <div class="flex flex-col" v-if="method == 'post'">
            <MoneyInputFormField
              field="tax_fee"
              name="Tasa administrativa"
              :form="form"
            />
          </div>
          <div class="flex flex-col">
            <MoneyInputFormField
              field="total_price"
              name="Precio sin iva con tasa"
              :form="form"
            />
          </div>
          <div class="flex flex-col">
            <MoneyInputFormField
              field="total_price_to_pay"
              name="Precio total a pagar"
              :form="form"
            />
          </div>
          <div class="flex flex-col">
            <MoneyInputFormField
              field="total_price_localiza"
              name="Valor OC"
              :form="form"
            />
          </div>
        </div>
      </FormField>

      <FormField>
        <div class="grid grid-cols-3 gap-2">
          <div class="flex flex-col">
            <SelectFormField
              field="franchise"
              name="Franquicia"
              :form="form"
              :options="franchisesOptions"
            />
          </div>
          <div class="flex flex-col">
            <InputFormField field="reserve_code" name="Código de reserva" :form="form" />
          </div>
          <div class="flex flex-col">
            <InputFormField field="user" name="Referido" :form="form" />
          </div>
        </div>
      </FormField>

      <FormField>
        <div class="grid grid-cols-3 gap-2">
          <div class="flex flex-col">
            <SelectFormField
              field="monthly_mileage"
              name="Kilometraje"
              :form="form"
              :options="monthlyMileagesOptions"
            />
          </div>
          <div class="flex flex-col">
            <CheckboxInputFormField
              field="total_insurance"
              name="Seguro Total"
              :form="form"
            />
          </div>
          <div class="flex flex-col">
            <SelectFormField
              field="status"
              name="Estado"
              :form="form"
              :options="reservationStatusOptions"
            />
          </div>
        </div>
      </FormField>
    </template>

    <template #actions>
      <div class="grid grid-cols-2 gap-4 place-content-between h-48">
        <div class="text-start" v-if="form.id">
          <DeleteButton :action="route('reservations.destroy', form.id)"></DeleteButton>
        </div>
        <div class="text-start space-x-2">
          <SubmitButton :form="form"></SubmitButton>
          <CancelButton :action="route('reservations.index')" />
        </div>
      </div>
    </template>
  </FormSection>
</template>

<script setup>
import InputFormField from "@/Rentacar/Components/FormFields/InputFormField.vue";
import NumberInputFormField from "@/Rentacar/Components/FormFields/NumberInputFormField.vue";
import CheckboxInputFormField from "@/Rentacar/Components/FormFields/CheckboxInputFormField.vue";
import SelectFormField from "@/Rentacar/Components/FormFields/SelectFormField.vue";
import DateFormField from "@/Rentacar/Components/FormFields/DateFormField.vue";
import MoneyInputFormField from "@/Rentacar/Components/FormFields/MoneyInputFormField.vue";
import HourFormField from "@/Rentacar/Components/FormFields/HourFormField.vue";
import CancelButton from "@/Rentacar/Components/Buttons/CancelButton.vue";
import DeleteButton from "@/Rentacar/Components/Buttons/DeleteButton.vue";
import SubmitButton from "@/Rentacar/Components/Buttons/SubmitButton.vue";
import FormSection from "@/Components/FormSection.vue";

import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { formSubmit } from "@/Rentacar/Functions/form";
import FormField from "@/Rentacar/Components/FormFields/FormField.vue";

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
    text: identificationType.text,
  }))
);

const monthlyMileagesOptions = computed(() =>
  usePage().props.monthly_mileages.map((monthlyMileage) => ({
    value: monthlyMileage.value,
    text: monthlyMileage.value,
  }))
);
</script>

<style lang="scss" scoped></style>
