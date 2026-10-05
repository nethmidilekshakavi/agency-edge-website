<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        $replyTo = filter_var($this->lead->contact, FILTER_VALIDATE_EMAIL)
            ? [new Address($this->lead->contact, $this->lead->name)]
            : [];

        return new Envelope(
            subject: 'New enquiry: '.$this->lead->name.($this->lead->company ? ' ('.$this->lead->company.')' : ''),
            replyTo: $replyTo,
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.new-lead');
    }
}
