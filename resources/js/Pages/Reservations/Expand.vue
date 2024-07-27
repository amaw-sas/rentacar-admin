<template>
    <div class="p-4 flex flex-row gap-2">
        <div class="flex flex-col gap-2 w-full max-w-80">
            <Card>
                <CardList>
                    <CardListItem>
                        <CardListItemInfo
                            label="Nombre"
                            :value="item.fullname"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Tipo ID"
                            :value="item.identification_type"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Identificación"
                            :value="item.identification"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Teléfono"
                            :value="item.phone"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo label="Email" :value="item.email" />
                    </CardListItem>
                </CardList>
            </Card>
        </div>
        <div class="flex flex-col gap-2 w-full max-w-80">
            <Card>
                <CardList>
                    <CardListItem>
                        <CardListItemInfo
                            label="Categoría"
                            :value="item.category"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Lugar recogida"
                            :value="item.pickup_location"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Día recogida"
                            :value="item.pickup_date"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Hora recogida"
                            :value="item.pickup_hour"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Lugar retorno"
                            :value="item.return_location"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Día retorno"
                            :value="item.return_date"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Hora retorno"
                            :value="item.return_hour"
                        />
                    </CardListItem>
                </CardList>
            </Card>
        </div>
        <div class="flex flex-col gap-2 w-full max-w-80">
            <Card>
                <CardList>
                    <CardListItem>
                        <CardListItemInfo
                            label="Días reservados"
                            :value="item.selected_days"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Precio horas extra"
                            :value="item.extra_hours_price"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Precio seguro"
                            :value="item.coverage_price"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Tasa administrativa"
                            :value="item.tax_fee"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo label="IVA" :value="item.iva_fee" />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Precio total"
                            :value="item.total_price"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Precio Localiza"
                            :value="item.total_price_localiza"
                        />
                    </CardListItem>
                </CardList>
            </Card>
        </div>
        <div class="flex flex-col gap-2 w-full max-w-80">
            <Card>
                <CardList>
                    <CardListItem>
                        <CardListItemInfo
                            label="Franquicia"
                            :value="item.franchise"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo label="Referido" :value="item.user" />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Código de reserva"
                            :value="item.reserve_code"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Kilometraje"
                            :value="item.monthly_mileage"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo label="Estado" :value="item.status" />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Fecha creación"
                            :value="item.created_at"
                        />
                    </CardListItem>
                    <CardListItem>
                        <CardListItemInfo
                            label="Fecha actualización"
                            :value="item.updated_at"
                        />
                    </CardListItem>
                </CardList>
            </Card>
        </div>
    </div>
</template>

<script setup>
import Card from "@/Rentacar/Components/Cards/Card.vue";
import CardList from "@/Rentacar/Components/Cards/CardList.vue";
import CardListItem from "@/Rentacar/Components/Cards/CardListItem.vue";
import CardListItemInfo from "@/Rentacar/Components/Cards/CardListItemInfo.vue";

const props = defineProps({
    item: Object,
});
</script>
