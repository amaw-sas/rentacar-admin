<template>
    <div>
        <DangerButton @click.prevent="confirmReservationDeletion = true">
            <VueFeather type="trash" />
            <span class="ml-2">Borrar</span>
        </DangerButton>

        <ConfirmationModal
            :show="confirmReservationDeletion"
            @close="confirmReservationDeletion = false"
        >
            <template #title>Borrar elemento</template>

            <template #content>
                ¿Está seguro de borrar este elemento?
            </template>

            <template #footer>
                <SecondaryButton
                    class="mr-2"
                    @click.native="confirmReservationDeletion = false"
                >
                    <VueFeather type="x" />
                    <span class="ml-2">Cancelar</span>
                </SecondaryButton>

                <Link
                    id="delete-button"
                    as="button"
                    type="button"
                    class="bg-red-300 hover:bg-red-400 text-red-800 font-bold py-2 px-4 rounded inline-flex items-center"
                    :href="action"
                    alt="Borrar"
                    method="delete"
                >
                    <VueFeather type="trash" />
                    <span class="ml-2">Borrar</span>
                </Link>
            </template>
        </ConfirmationModal>
    </div>
</template>

<script setup>
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import VueFeather from "vue-feather";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    action: String,
});

const confirmReservationDeletion = ref(false);
</script>
