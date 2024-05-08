<template>
    <div style="padding: 15px">
        <ul style="list-style: square">
            <li>Nombre: {{ item.fullname }}</li>
            <li>Tipo ID: {{ item.identification_type }}</li>
            <li>ID: {{ item.identification }}</li>
            <li>Teléfono: {{ item.phone }}</li>
            <li>Email: {{ item.email }}</li>
            <li>Categoria: {{ item.category }}</li>
            <li>
                Lugar recogida:
                {{ item.pickup_location }}
            </li>
            <li>Día recogida: {{ item.pickup_date }}</li>
            <li>Hora recogida: {{ item.pickup_hour }}</li>
            <li>
                Lugar retorno:
                {{ item.return_location }}
            </li>
            <li>Día retorno: {{ item.return_date }}</li>
            <li>Hora retorno: {{ item.return_hour }}</li>
            <li>Días reservados: {{ item.selected_days }}</li>
            <li>Horas extra: {{ item.extra_hours }}</li>
            <li>Precio horas extra: {{ item.extra_hours }}</li>
            <li>Días seguro: {{ item.coverage_days }}</li>
            <li>Precio seguro: {{ item.coverage_price }}</li>
            <li>Tarifa administrativa: {{ item.tax_fee }}</li>
            <li>IVA: {{ item.iva_fee }}</li>
            <li>Precio total: {{ item.total_price }}</li>
            <li>Precio total Localiza: {{ item.total_price_localiza }}</li>
            <li>Franquicia: {{ item.franchise }}</li>
            <li>Referido: {{ item.user }}</li>
            <li>Código de reserva: {{ item.reserve_code }}</li>
            <li>Estado: {{ item.status }}</li>
            <li>Fecha creación: {{ item.created_at }}</li>
            <li>Fecha actualización: {{ item.updated_at }}</li>
        </ul>
    </div>
</template>

<script setup>
const props = defineProps({
    item: Object,
});
</script>
