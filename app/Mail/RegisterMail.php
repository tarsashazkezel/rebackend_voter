<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// use App\Models\User;
use Illuminate\Support\Facades\URL;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verificationUrl;
    public string $name;
    /**
     * Create a new message instance.
     */
    public function __construct( public string $token, public string $username)
    {
           // $this->verificationUrl = URL::temporarySignedRoute(
        //     'verification.verify',
        //     now()->addMinutes(60),
        //     [
        //         'id' => $user->id,
        //         'hash' => sha1($user->email),
        //     ]
        // );
        $this->name = $username;
        $this->verificationUrl = 'http://localhost:4200/register/confirm/' . $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Register your email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registration',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
