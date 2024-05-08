<template>
    <FormField>
        <InputLabel :for="field" :value="name" />
        {{ form[field] }}
        <InputError :message="form.errors[field]" class="mt-2" />
    </FormField>
</template>

<script setup>
import FormField from "@/Rentacar/Components/FormFields/FormField.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

defineProps({
    field: String,
    form: Object,
    name: String,
});
</script>

<style lang="scss" scoped></style>
