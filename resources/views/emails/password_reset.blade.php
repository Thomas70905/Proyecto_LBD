@component('mail::message')
# Acceso Temporal Generado

Para recuperar el acceso a tu cuenta, ingresa al sistema con la siguiente contraseña temporal:

@component('mail::panel')
{{ $temporaryPassword }}
@endcomponent

Gracias,<br>
Veterinaria Moncada siempre a tu servicio.
@endcomponent