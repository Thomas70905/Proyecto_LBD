@component('mail::message')
# Confirmación de Cita

Hola {{ $appointmentData['nombre_cliente'] }},

Tu cita ha sido agendada exitosamente. Aquí están los detalles:

@component('mail::panel')
**Mascota:** {{ $appointmentData['nombre_mascota'] }}  
**Servicio:** {{ $appointmentData['nombre_servicio'] }}  
**Fecha:** {{ $appointmentData['fecha'] }}  
**Hora:** {{ $appointmentData['hora'] }}  
**Descripción:** {{ $appointmentData['descripcion'] }}
@endcomponent

Por favor, asegúrate de llegar 10 minutos antes de tu cita.

Gracias,<br>
Veterinaria Moncada siempre a tu servicio.
@endcomponent 