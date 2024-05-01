export function formatMoney(price) {
    if (price)
        return new Intl.NumberFormat("es-CO", {
            style: "currency",
            currency: "COP",
        }).format(price);
}
