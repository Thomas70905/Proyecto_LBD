<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Clase para el manejo de notificaciones por correo electrónico de citas.
 * 
 * Esta clase se encarga de generar y enviar correos electrónicos de notificación
 * relacionados con las citas en el sistema. Proporciona:
 * 
 * 1. Configuración del correo:
 *    - Asunto personalizado
 *    - Plantilla markdown para el contenido
 *    - Datos de la cita para la plantilla
 * 
 * 2. Características:
 *    - Envío en cola (Queueable)
 *    - Serialización de modelos
 *    - Plantilla responsive
 *    - Personalización de datos
 * 
 * 3. Integración:
 *    - Compatible con el sistema de correo de Laravel
 *    - Utiliza el facade Mail para el envío
 *    - Soporte para markdown en las plantillas
 * 
 * Ejemplo de uso:
 * ```php
 * $appointmentData = [
 *     'fecha' => '2024-03-15',
 *     'hora' => '14:30',
 *     'mascota' => 'Fido',
 *     'servicio' => 'Consulta General'
 * ];
 * 
 * Mail::to($user->email)->send(new AppointmentNotificationMail($appointmentData));
 * ```
 * 
 * @package App\Mail
 */
class AppointmentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Datos de la cita que se utilizarán en la plantilla del correo.
     * 
     * Este array contiene toda la información necesaria para generar
     * el contenido del correo, incluyendo:
     * 
     * - Fecha y hora de la cita
     * - Nombre de la mascota
     * - Tipo de servicio
     * - Información del cliente
     * - Detalles adicionales
     * 
     * @var array
     */
    public array $appointmentData;

    /**
     * Constructor de la clase.
     * 
     * Inicializa la clase con los datos de la cita que se utilizarán
     * para generar el contenido del correo.
     * 
     * @param array $appointmentData Datos de la cita para la plantilla
     */
    public function __construct(array $appointmentData)
    {
        $this->appointmentData = $appointmentData;
    }

    /**
     * Configura el sobre del correo electrónico.
     * 
     * Define el asunto y otros metadatos del correo.
     * El asunto se establece como "Confirmación de Cita - Veterinaria Moncada"
     * para identificar claramente el propósito del correo.
     * 
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Cita - Veterinaria Moncada',
        );
    }

    /**
     * Configura el contenido del correo electrónico.
     * 
     * Especifica la plantilla markdown a utilizar y los datos
     * que se pasarán a la plantilla. La plantilla se encuentra
     * en 'emails.appointment_notification' y recibe los datos
     * de la cita para su renderizado.
     * 
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointment_notification',
            with: [
                'appointmentData' => $this->appointmentData,
            ]
        );
    }

    /**
     * Obtiene los archivos adjuntos para el correo.
     * 
     * En este caso, no se incluyen archivos adjuntos.
     * Si se necesitan adjuntar archivos en el futuro,
     * se pueden agregar aquí.
     * 
     * @return array Array vacío ya que no hay adjuntos
     */
    public function attachments(): array
    {
        return [];
    }
} 