@component('mail::message')
Estimado/a {{ $fullname }},

# Su reserva está {{ $reservation_status }}

El código asociado a la reserva es: **{{ $reserve_code }}** \
Nombre del Arrendador: **{{ $reserva->fullname }}** \
{{ $reserva->short_identification_type }} **{{ $reserva->identification }}**

ÚNICO RESERVANTE AUTORIZADO

---

<div class="mytable">
<div class="col">

**RECOGIDA**

**Sucursal:** {{ $pickup_branch_name }} \
**Dirección:** {{ $pickup_branch_address }} <a href="{{ $pickup_branch_map }}" target="_blank">Ver Mapa</a> \
**Fecha y Hora:** {{ $pickup_date }} {{ $pickup_hour }}

</div>
<div class="col">

**DEVOLUCIÓN**

**Sucursal:** {{ $return_branch_name }} \
**Dirección:** {{ $return_branch_address }} <a href="{{ $return_branch_map }}" target="_blank">Ver Mapa</a> \
**Fecha y Hora:** {{ $return_date }} {{ $return_hour }}

</div>
</div>

---

<div class="mytable">
<div class="col">

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

</div>
<div class="col">

**GAMA RESERVADA**

{{ $category_name }}  \
![categoria]({{ $category_image }} "{{ $category_name }}")  \
**{{ $category_category }}**

</div>
</div>

**El valor Incluye:** {{ $included_fees }} \
**Método de pago:** Tarjeta de Crédito en Sede

---

**Para tener en cuenta:** \
Los descuentos adquiridos en esta reserva son Intransferibles.

Solicitar cambios puede afectar los descuentos adquiridos, verifique que la información sea correcta
o de lo contrario solicite cuanto antes una nueva reserva.

Preséntese en el lugar de recogida 30 minutos antes de la hora programada con los siguientes
documentos:

1. Tarjeta de Crédito
2. Cédula ó Pasaporte
3. Licencia de Conducción vigente

**CONDICIONES**

**IMPORTANTE:** Tener en cuenta que cualquier cambio sobre su reserva puede incurrir en perder el
descuento ya obtenido, por lo cual, quedaría sujeto a descuentos actuales.

**Antes de recoger el vehículo:**

*Recuerde llevar en físico:*

- Tarjeta de crédito
- Documento de identidad y licencia de conducción:
  - *Colombiano residente en el extranjero*: pasaporte y licencia extranjera
  - *Extranjero*: documento de extranjería o pasaporte y licencia extranjera
  - *Colombiano residente en Colombia*: cédula colombiana y licencia de conducción colombiana
- Presentarse 30 minutos antes o de forma puntual a la hora acordada.

Verifique:

- El cupo de su tarjeta de crédito.
- La fecha de vencimiento de su tarjeta
- La fecha de vencimiento de su licencia.

**IMPORTANTE:** \
**Conductor adicional:** \
Si el vehículo será conducido por otra(s) persona(s) diferente(s) al titular del contrato, se debe
cancelar en la agencia un cargo adicional de $ 12.000 pesos diarios por su seguro ya que se hace
responsable del vehículo.

- El Titular de la tarjeta de crédito y los conductores adicionales deben estar presentes para la firma
de contratos.
- Sólo se reciben pagos con tarjetas de crédito físicas, NO se aceptan pagos en efectivo ni otros
medios de pago.

**Durante la recogida del vehículo:**

- Puede adquirir o rechazar servicios adicionales según su necesidad como son: Seguro total, seguro
de conductor adicional, entrega en otras sedes, lavada prepagada, silla de bebé y GPS (los 2 últimos
bajo disponibilidad de la agencia).
- Elija el vehículo de su agrado según disponibilidad y gama seleccionada.

- **Verifique** que el vehículo esté limpio y con el tanque lleno.
- Realice un registro fotográfico del vehículo si lo considera necesario.

**Durante el periodo de renta**

- En caso de emergencia o asistencia en carretera comunícate de inmediato con las líneas de
atención, las 24 horas del día, los 365 días del año. \
Línea de atención **AUTOSEGURO las 24 horas / 4-4442001 Asistencia #570**
- Evite multas, tenga en cuenta las restricciones de movilidad “pico y placa” de las diferentes
ciudades por donde transite.
- Puede recorrer todo el país, si adquirió una mensualidad tenga en cuenta los kilómetros contratados
para evitar sobrecostos.
- No puede ser usado para trabajos en aplicaciones de movilidad como uber, cafity o similares.
- El vehículo no puede salir del país.

**Antes de retornar el vehículo**

- Verifique que el tanque esté lleno y el vehículo limpio para evitar costos adicionales.
- Verifique el interior del vehículo y no olvide sus artículos personales.

**CONDICIONES DE ENTREGA Y RETORNO DEL VEHÍCULO**

**Importante tener presente:**

- En caso de incluir el servicio de lavado al momento de solicitar la reserva, esta tiene un costo
de $20.000 IVA incluido.
- Si por el contrario el servicio se solicita al momento de retirar el vehículo en agencia o en la
devolución, el valor a cancelar es de $30.000 IVA incluido.
- Tendrá cobros adicionales solo en los casos en que lleve sus mascotas, el carro regrese con
algún olor fuerte a cigarrillo o alcohol, o si ha conducido en condiciones adversas y se evidencie
exceso de barro. Si este es su caso, le informo que la lavada tendrá un costo full con aspirada:
$150.000 o full con aspirada y tapicería: $225.000.

Apreciamos su paciencia y confianza en nuestros servicios.

Atentamente,

@yield('franchise')
Travel: Amaw SAS \
Código: 07334927
@endcomponent
