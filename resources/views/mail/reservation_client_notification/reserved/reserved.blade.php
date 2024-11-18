@component('mail::message')

<div class="aproved">

# Su reserva está {{ $reservation_status }}

# El código de reserva: **{{ $reserve_code }}**

</div>

<div class="section centered">

**GAMA RESERVADA**

![categoria]({{ $category_image }} "{{ $category_name }}")  \
{{ $category_name }}  \
**{{ $category_category }}**

Arrendador: **{{ $reserva->fullname }}** \
Contacto: **{{ $reserva->phone }}** \
{{ $reserva->short_identification_type }} **{{ $reserva->identification }}** \
Único Reservante Autorizado

</div>

<div class="section">

**RECOGIDA**

**Sucursal:** {{ $pickup_branch_name }} \
**Dirección:** {!! $pickup_branch_address !!} <a href="{{ $pickup_branch_map }}" target="_blank">Ver Mapa</a> \
**Fecha y Hora:** {{ $pickup_date }} {{ $pickup_hour }}

</div>
<div class="section">

**DEVOLUCIÓN**

**Sucursal:** {{ $return_branch_name }} \
**Dirección:** {!! $return_branch_address !!} <a href="{{ $return_branch_map }}" target="_blank">Ver Mapa</a> \
**Fecha y Hora:** {{ $return_date }} {{ $return_hour }}

</div>

<div class="section">

**DATOS FINANCIEROS**

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
**Valor total**: {{ $total_fee }}

**El valor Incluye:** {{ $included_fees }} \
**Método de pago:** Tarjeta de Crédito en Sede \
**Descuentos:** Los descuentos adquiridos en esta reserva son Intransferibles y no se admiten cambios.
Verifique que la información sea correcta o de lo contrario solicite cuanto antes una nueva reserva.
Realizar una nueva reserva no garantiza obtener los descuentos de una reserva previa.

</div>

<div class="section">

**ANTES DE RECOGER EL VEHÍCULO**

Preséntese en el lugar de recogida 30 minutos antes de la hora programada con los siguientes
documentos:

1. **Tarjeta de Crédito** \
Sólo se reciben pagos con tarjetas de crédito físicas, NO se aceptan pagos en efectivo ni otros
medios de pago.
2. **Cédula ó Pasaporte**
3. **Licencia de Conducción** \
La licencia de conducción determina el documento de identificación a presentar, si tiene una licencia de conducción
colombiana, debe presentar su cédula colombiana (no se acepta pasaporte). Si tiene una licencia de conducción extranjera
debe presentar su pasaporte, incluso si es colombiano residente en el exterior.

Verifique el cupo y la fecha de vencimiento de su tarjeta de crédito y la fecha de vencimiento de su licencia de conducción.

</div>

<div class="section">

**CONDUCTOR ADICIONAL**

Si el vehículo será conducido por otra(s) persona(s) diferente(s) al titular del contrato, se debe
cancelar en la agencia un cargo adicional de $ 12.000 pesos diarios por su seguro ya que se hace
responsable del vehículo. Los conductores adicionales y el titular de la tarjeta de crédito deben
estar presentes para la firma de contratos.
</div>

<div class="section">

**DURANTE LA RECOGIDA DEL VEHÍCULO**

- Elija el vehículo de su agrado según disponibilidad y gama seleccionada (tenga en cuenta la restricciones de movilidad de las zonas a transitar).
- Verifique que el vehículo esté limpio y con el tanque lleno.
- Realice un registro fotográfico del vehículo si lo considera necesario.
- Puede adquirir o rechazar servicios adicionales según su necesidad como son: Seguro total, seguro
de conductor adicional, entrega en otras sedes, lavada prepagada, silla de bebé y GPS (los 2 últimos
bajo disponibilidad de la agencia).

</div>

<div class="section">

**DURANTE EL PERIODO DE RENTA**

- En caso de emergencia o asistencia en carretera comunícate de inmediato con las líneas de
atención, las 24 horas del día, los 365 días del año. \
Línea de atención **AUTOSEGURO las 24 horas / 4-4442001 Asistencia #570**
- Evite multas, tenga en cuenta las restricciones de movilidad “pico y placa” de las diferentes
ciudades por donde transite.
- Puede recorrer todo el país, si adquirió una mensualidad tenga en cuenta los kilómetros contratados
para evitar sobrecostos.
- No puede ser usado para trabajos en aplicaciones de movilidad como uber, cafity o similares.
- El vehículo no puede salir del país.

</div>

<div class="section">

**ANTES DE RETORNAR EL VEHÍCULO**

- Verifique que el tanque esté lleno y el vehículo limpio para evitar costos adicionales.
- Verifique el interior del vehículo y no olvide sus artículos personales.

</div>


<div class="section">

**LAVADO DE VEHÍCULO**

El vehículo debe entregarse en las mismas condiciones de limpieza en que lo recibió.
Contamos con el servicio de lavado al momento de hacer su reserva, el costo será de $20.000 IVA incluido.
Si, por el contrario, solicita el servicio al momento de devolver el vehículo en la agencia, el valor a pagar será de $30.000 IVA incluido.

Cabe destacar que se aplicarán cobros adicionales en los siguientes casos:

Si transportó mascotas en el vehículo.
Si el vehículo regresa con olor fuerte a cigarrillo o alcohol.
Si condujo en condiciones adversas y se evidencia exceso de barro.
En estos casos, el servicio de lavado tendrá un costo de:

Lavado completo con aspirado: $150.000 IVA incluido.
Lavado completo con aspirado y tapicería: $225.000 IVA incluido.

</div>

@endcomponent
