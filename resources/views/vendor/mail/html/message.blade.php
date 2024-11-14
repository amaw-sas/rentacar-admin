@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
@endcomponent
@endslot

{{-- Body --}}


<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td align="center" valign="middle">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; font-family: Arial, sans-serif;">
<tr>
<td style="padding: 20px; background-color: #ffffff;">

{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

</td>
</tr>
<tr>
<td style="padding: 10px; background-color: #e9ecef;">
<p style="color: #7a7a7a; font-size: 12px;">
Atentamente,
<br>
<br>

@yield('franchise')

<div style="color: #7a7a7a; font-size: 12px;">
Travel: Amaw SAS <br>
Código: 07334927 <br>
</div>
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>

@endcomponent
