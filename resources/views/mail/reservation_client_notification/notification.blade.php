@component('mail::message')

Estimado/a {{ $reserva->fullname }},

Le informamos que su solicitud de reserva está en el estado <b>{{ $reserva->formatted_client_reservation_status }}</b>.
Estamos revisándola y le proporcionaremos una respuesta tan pronto como sea posible.

El código asociado a la reserva es: <b>{{ $reserva->reserve_code }}</b>

Adjuntamos copia de su solicitud:

---
<br/>

Nombre: **{{ $reserva->fullname }}** <br/>
{{ $reserva->identification_type }}: **{{ $reserva->identification }}** <br/>
Teléfono: **{{ $reserva->phone }}** <br/>
Email: **{{ $reserva->email }}** <br/>

<br/>

**{{ $reserva->formatted_category }}** <br/>
Recogida: {{ $reserva->formatted_pickup_place }} <br/>
Fecha: {{ $reserva->formatted_pickup_date }} {{ $reserva->formatted_pickup_hour }} <br/>
Retorno: {{ $reserva->formatted_return_place }} <br/>
Fecha: {{ $reserva->formatted_return_date }} {{ $reserva->formatted_return_hour }} <br/>

@if ($reserva->total_insurance)
El cliente requiere seguro total
@endif

@if ($reserva->monthly_mileage)
El cliente ha seleccionado el kilometraje: {{ $reserva->monthly_mileage }}
@endif

---
<br/>

Apreciamos su paciencia y confianza en nuestros servicios.

Atentamente,

@yield('franchise')
Travel: Amaw SAS <br/>
Código: 07334927 <br/>
@endcomponent
