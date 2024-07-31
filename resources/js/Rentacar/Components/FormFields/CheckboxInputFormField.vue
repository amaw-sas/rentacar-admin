<template>
    <FormField>
        <InputLabel :for="field" :value="name" />
        <input
            :id="field"
            :ref="field"
            type="checkbox"
            class="checkbox mt-2"
            v-model="form[field]"
        />
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
});
</script>
