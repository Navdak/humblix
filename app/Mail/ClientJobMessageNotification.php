<?php

namespace App\Mail;

use App\Models\ClientJob;
use App\Models\JobMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientJobMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ClientJob $clientJob, public JobMessage $message)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "HUMELIX job update: {$this->clientJob->job_reference}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.client-jobs.message-notification',
        );
    }
}
