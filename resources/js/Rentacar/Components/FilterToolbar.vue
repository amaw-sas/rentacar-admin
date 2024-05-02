<template>
    <form
        class="flex flex-1 justify-end"
        id="filter_form"
        ref="filter_form"
        @submit.prevent="filterData"
    >
        <div
            class="flex flex-1 flex-col justify-end space-y-2 md:flex-row md:space-x-2 md:space-y-0"
        >
            <slot name="custom-filters"></slot>
            <!-- free search -->
            <JetInput
                v-model="s"
                id="free_search_input"
                type="text"
                :placeholder="searchPlaceholder"
                class="p-2"
            ></JetInput>

            <input v-if="s" type="hidden" name="s" :value="s" />

            <JetButton
                type="submit"
                id="filter-button"
                class="space-x-2 justify-center md:justify-start"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 32 32"
                    width="24"
                    height="24"
                    fill="white"
                >
                    <title>filter</title>
                    <path
                        d="M18,28H14a2,2,0,0,1-2-2V18.41L4.59,11A2,2,0,0,1,4,9.59V6A2,2,0,0,1,6,4H26a2,2,0,0,1,2,2V9.59A2,2,0,0,1,27.41,11L20,18.41V26A2,2,0,0,1,18,28ZM6,6V9.59l8,8V26h4V17.59l8-8V6Z"
                    />
                    <path
                        fill="none"
                        d="M0 0H32V32H0z"
                        data-name="&lt;Transparent Rectangle>"
                    />
                </svg>
                <span class="block md:hidden">Filtrar</span>
            </JetButton>
            <JetButton
                type="button"
                id="filter-clean-button"
                @click="clearFilters"
                class="space-x-2 justify-center md:justify-start"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 32 32"
                    width="24"
                    height="24"
                    fill="white"
                >
                    <title>Limpiar</title>
                    <path d="M20 18H26V20H20z" transform="rotate(-180 23 19)" />
                    <path d="M24 26H30V28H24z" transform="rotate(-180 27 27)" />
                    <path d="M22 22H28V24H22z" transform="rotate(-180 25 23)" />
                    <path
                        d="M17.0029,20a4.8952,4.8952,0,0,0-2.4044-4.1729L22,3,20.2691,2,12.6933,15.126A5.6988,5.6988,0,0,0,7.45,16.6289C3.7064,20.24,3.9963,28.6821,4.01,29.04a1,1,0,0,0,1,.96H20.0012a1,1,0,0,0,.6-1.8C17.0615,25.5439,17.0029,20.0537,17.0029,20ZM11.93,16.9971A3.11,3.11,0,0,1,15.0041,20c0,.0381.0019.208.0168.4688L9.1215,17.8452A3.8,3.8,0,0,1,11.93,16.9971ZM15.4494,28A5.2,5.2,0,0,1,14,25H12a6.4993,6.4993,0,0,0,.9684,3H10.7451A16.6166,16.6166,0,0,1,10,24H8a17.3424,17.3424,0,0,0,.6652,4H6c.031-1.8364.29-5.8921,1.8027-8.5527l7.533,3.35A13.0253,13.0253,0,0,0,17.5968,28Z"
                    />
                    <path
                        fill="none"
                        d="M0 0H32V32H0z"
                        data-name="&lt;Transparent Rectangle>"
                    />
                </svg>
                <span class="block md:hidden">Limpiar</span>
            </JetButton>
        </div>
    </form>
</template>

<script setup>
import JetInput from "@/Components/TextInput.vue";
import JetButton from "@/Components/PrimaryButton.vue";
import { goToGet } from "@/Rentacar/Functions/util.js";
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    searchPlaceholder: {
        type: String,
        default: "Escriba aquí palabras",
    },
    cleanUrl: String,
});

const emit = defineEmits(["filterData"]);

const filter_form = ref(null);

const s = ref(usePage().props?.elements?.meta?.s ?? null);

function filterData() {
    const data = Object.fromEntries(
        new FormData(document.querySelector("#filter_form")).entries()
    );
    emit("filterData", data);
}

function clearFilters() {
    goToGet(
        props.cleanUrl,
        {},
        {
            preserveState: false,
            preserveScroll: true,
            only: ["paginator"],
        }
    );
}
</script>
