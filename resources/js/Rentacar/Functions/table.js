import { usePage, router } from "@inertiajs/vue3";
import { format, parse } from "date-fns";
import { es } from "date-fns/locale";

export function filterData(url, data) {
    router.get(url, data, {
        preserveState: false,
        preserveScroll: true,
        only: ["paginator"],
    });
}

export function getFilterFormData() {
    return Object.fromEntries(
        new FormData(document.querySelector("#filter_form")).entries()
    );
}

export function getOrderByData() {
    const orderByData = {};
    orderByData["orderByCol"] = usePage().props?.elements?.meta?.orderBy?.col;
    orderByData["orderOrientation"] =
        usePage().props?.elements?.meta?.orderBy?.order;
    return orderByData;
}

export function showHumanFriendlyDate(
    date,
    date_format,
    output_date_form = "dd MMMM yyyy"
) {
    const friendlyDate = parse(date, date_format, new Date());
    return format(friendlyDate, output_date_form, { locale: es });
}
