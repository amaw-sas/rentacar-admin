export function formSubmit(form, method, url) {
    form.transform((data) => ({
        ...data,
        _method: method,
    })).post(url);
}
