<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $temporaryPassword;

    public function __construct(string $temporaryPassword)
    {
        $this->temporaryPassword = $temporaryPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contraseña Temporal de Acceso',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.password_reset',
            with: [
                'temporaryPassword' => $this->temporaryPassword,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}