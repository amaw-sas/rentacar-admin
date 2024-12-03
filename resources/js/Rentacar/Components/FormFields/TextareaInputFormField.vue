<template>
    <FormField>
        <InputLabel :for="field" :value="name" />
        <textarea
            v-model="form[field]"
            :ref="field"
            :id="field"
            :cols="cols"
            :rows="rows"
            class="mt-1 block w-full input border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        ></textarea>
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
    rows: {
        type: Number,
        required: false,
        default: 20,
    },
    cols: {
        type: Number,
        required: false,
        default: 50,
    },
});
</script>

<style lang="scss" scoped></style>
