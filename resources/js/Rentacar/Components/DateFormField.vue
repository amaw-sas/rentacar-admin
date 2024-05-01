<template>
    <FormField>
        <InputLabel :for="field" :value="name" />
        <VueDatePicker
            v-model="form[field]"
            model-type="yyyy-MM-dd"
            format="yyyy-MM-dd"
            inline
            auto-apply
            locale="es"
            :timezone="{ tz: 'America/New_York', offset: -5 }"
            :clearable="false"
            :enable-time-picker="false"
        />
        <InputError :message="form.errors[field]" class="mt-2" />
    </FormField>
</template>

<script setup>
import FormField from "@/Rentacar/Components/FormField.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

defineProps({
    field: String,
    form: Object,
    name: String,
});
</script>

<style lang="scss" scoped></style>
