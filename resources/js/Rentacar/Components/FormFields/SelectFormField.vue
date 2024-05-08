<template>
    <FormField>
        <InputLabel :for="field" :value="name" />
        <select
            :id="field"
            :ref="field"
            v-model="form[field]"
            class="mt-1 block w-full"
        >
            <option
                v-for="option in options"
                :key="field + option.value"
                :value="option.value"
                v-text="option.text"
            ></option>
        </select>
        <InputError :message="form.errors[field]" class="mt-2" />
    </FormField>
</template>

<script setup>
import FormField from "@/Rentacar/Components/FormFields/FormField.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

defineProps({
    field: String,
    form: Object,
    name: String,
    options: Array,
});
</script>

<style lang="scss" scoped></style>
