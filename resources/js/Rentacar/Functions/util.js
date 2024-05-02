import { router } from "@inertiajs/vue3";

export function goToPost(url, data, options) {
    goTo("post", url, data, options);
}

export function goToGet(url, data, options) {
    goTo("get", url, data, options);
}

export function goToDelete(url, data, options) {
    goTo("delete", url, data, options);
}

export function goTo(method, url, data = {}, options) {
    if (!options) {
        options = {
            preserveState: true,
            preserveScroll: true,
            // only: ["paginator"],
        };
    }

    router[method](url, data, options);
}
