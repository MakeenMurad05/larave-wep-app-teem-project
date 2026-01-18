<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $newUser;
    public $creator;

    public function __construct(User $newUser, User $creator)
    {
        $this->newUser = $newUser;
        $this->creator = $creator;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تنبيه: مستخدم جديد - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
                htmlString: "اسم المستخدم الجديد هو: " . $this->newUser->name,
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
