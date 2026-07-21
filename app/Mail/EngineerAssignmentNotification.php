<?php

namespace App\Mail;

use App\Models\Engineer;
use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EngineerAssignmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry, public Engineer $engineer)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "HUMELIX assignment: {$this->enquiry->reference_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enquiries.engineer-assignment',
        );
    }
}
