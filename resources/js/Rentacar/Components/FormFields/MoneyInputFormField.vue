<template>
    <FormField>
        <InputLabel :for="field" :value="name" />
        <Money3Component
            :id="field"
            :ref="field"
            v-model="form[field]"
            class="input input-bordered mt-1 block w-full"
            v-bind="vueMoney3Config"
        ></Money3Component>
        <InputError :message="form.errors[field]" class="mt-2" />
    </FormField>
</template>

<script setup>
import FormField from "@/Rentacar/Components/FormFields/FormField.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { Money3Component } from "v-money3";

import { ref } from "vue";

defineProps({
    field: String,
    form: Object,
    name: String,
});

const vueMoney3Config = ref({
    masked: false,
    prefix: "$ ",
    suffix: "",
    thousands: ",",
    decimal: ".",
    precision: 0,
    disableNegative: false,
    disabled: false,
    min: null,
    max: null,
    allowBlank: false,
    minimumNumberOfCharacters: 0,
    shouldRound: false,
    focusOnRight: false,
    number: true,
});
</script>

<style lang="scss" scoped></style>
