@component('mail::message')

Estimado/a {{ $reserva->fullname }},

Le informamos que su solicitud de reserva está en proceso.
Estamos revisándola y le proporcionaremos una respuesta tan pronto como sea posible.

En caso de que su solicitud sea aprobada,
recibirá un CÓDIGO DE RESERVA por correo electrónico.
Le recomendamos que revise su bandeja de entrada y la carpeta de spam.

Adjuntamos copia de su solicitud:

---
<br/>

Nombre: **{{ $reserva->fullname }}** <br/>
{{ $reserva->identification_type }}: **{{ $reserva->identification }}** <br/>
Teléfono: **{{ $reserva->phone }}** <br/>
Email: **{{ $reserva->email }}** <br/>

<br/>

**{{ $reserva->formattedCategory() }}** <br/>
Recogida: {{ $reserva->formattedPickupPlace() }} <br/>
Fecha: {{ $reserva->formattedPickupDate() }} {{ $reserva->formattedPickupHour() }} <br/>
Retorno: {{ $reserva->formattedReturnPlace() }} <br/>
Fecha: {{ $reserva->formattedReturnDate() }} {{ $reserva->formattedReturnHour() }} <br/>

@if ($total_insurance)
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
