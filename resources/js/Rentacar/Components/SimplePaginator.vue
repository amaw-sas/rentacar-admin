<template>
    <div class="flex flex-1 flex-col md:flex-row md:justify-between px-2 mt-3">
        <div
            class="flex flex-shrink flex-row space-x-5 justify-center md:justify-start"
        >
            <span v-if="first">
                <Link
                    id="first-link"
                    :href="first"
                    preserve-scroll
                    class="text-blue-500"
                >
                    &lt;&lt;
                </Link>
            </span>
            <span v-if="prev">
                <Link
                    id="prev-link"
                    :href="prev"
                    preserve-scroll
                    class="text-blue-500"
                >
                    &lt;
                </Link>
            </span>

            <span v-for="page_link in page_links">
                <Link
                    v-if="!page_link?.active"
                    :id="'page_link_' + page_link?.label"
                    :href="page_link?.url"
                    preserve-scroll
                    class="text-blue-500"
                >
                    {{ page_link?.label }}
                </Link>
                <span class="text-gray-600" v-else>{{ page_link?.label }}</span>
            </span>

            <span v-if="next">
                <Link
                    id="next-link"
                    :href="next"
                    preserve-scroll
                    class="text-blue-500"
                >
                    &gt;
                </Link>
            </span>
            <span v-if="last">
                <Link
                    id="last-link"
                    :href="last"
                    preserve-scroll
                    class="text-blue-500"
                >
                    &gt;&gt;
                </Link>
            </span>
        </div>
        <div
            class="flex flex-shrink flex-row space-x-3 justify-center md:justify-start"
        >
            <slot></slot>
            <span v-if="current_page" class="font-bold">
                Página: {{ current_page }}
            </span>
            <span v-if="current_page" class="font-bold">
                Total: {{ total_records }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    links: {
        type: Object,
        required: true,
    },
    meta: {
        type: Object,
        required: false,
    },
    only_data: {
        type: Array,
        required: false,
        default: ["elements"],
    },
});

const first = ref(props.links?.first);
const prev = ref(props.links?.prev);
const next = ref(props.links?.next);
const last = ref(props.links?.last);
const current_page = ref(props.meta?.current_page);
const total_records = ref(props.meta?.total);
const page_links = ref(
    props.meta?.links?.filter((page) => !isNaN(parseInt(page.label)))
);
const only_data = ref(props.only_data);
</script>
