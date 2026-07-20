<?php

namespace App\Mail;

use App\Models\Article;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewArticlePublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Article $article, public NewsletterSubscriber $subscriber)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New HUMELIX resource: '.$this->article->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter.new-article',
        );
    }
}
