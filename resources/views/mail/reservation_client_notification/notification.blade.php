@component('mail::message')
Estimado/a {{ $fullname }},

# Su reserva está {{ $reservation_status }}

El código asociado a la reserva es: **{{ $reserve_code }}**

Adjuntamos copia de su solicitud:

---

## Recogida:

{{ $pickup_branch_name }} [Ver Mapa]({{ $pickup_branch_map }} "{{ $pickup_branch_name }}")  \
{{ $pickup_branch_address }}  \
Fecha: {{ $pickup_date }} {{ $pickup_hour }}

## Retorno:

{{ $return_branch_name }} [Ver Mapa]({{ $return_branch_map }} "{{ $return_branch_name }}")  \
{{ $return_branch_address }}  \
Fecha: {{ $return_date }} {{ $return_hour }}

---

## Datos del Vehículo

![categoria]({{ $category_image }} "{{ $category_name }}")  \
{{ $category_name }}  \
{{ $category_category }}

---

## Datos Financieros

Tarifa con Dto ({{ $discount_percentage }}): {{ $daily_base_fee }}  \
Tarifa por ({{ $selected_days }}) días: {{ $base_fee }}  \
@if ($extra_hours)
\+ Horas extra ({{ $extra_hours }}): {{ $extra_hours_price }}  \
@endif
@if ($return_fee)
\+ Retorno otra sede: {{ $return_fee }}  \
@endif
Subtotal: {{ $subtotal_fee }}  \
\+ Tasa Admin (10%): {{ $tax_fee }}   \
\+ Impuesto IVA (19%): {{ $iva_fee }}  \
Total a pagar: {{ $total_fee }}

**El valor Incluye:** {{ $included_fees }}

**Método de pago:** Tarjeta de Crédito en Sede

---

## Datos del arrendador

Nombre: **{{ $reserva->fullname }}** \
{{ $reserva->short_identification_type }} **{{ $reserva->identification }}** - Único Reservante Autorizado

Los descuentos adquiridos en esta reserva son Intransferibles.

Solicitar cambios puede afectar los descuentos adquiridos, verifique que la Información sea correcta o de lo contrario solicite cuanto antes una nueva reserva.

Presentese en el lugar de recogida 30 minutos antes de la hora programada con los siguientes documentos:

1) Tarjeta de Crédito
2) Cédula ó Pasaporte
3) Licencia de Conducción vigente

---

Apreciamos su paciencia y confianza en nuestros servicios.

Atentamente,

@yield('franchise')
Travel: Amaw SAS \
Código: 07334927
@endcomponent
