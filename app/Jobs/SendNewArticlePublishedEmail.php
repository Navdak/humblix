<?php

namespace App\Jobs;

use App\Mail\NewArticlePublishedMail;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendNewArticlePublishedEmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    /**
     * @var array<int, int>
     */
    public array $backoff = [60, 300, 900];

    public function __construct(
        public int $articleId,
        public int $subscriberId,
    ) {
    }

    public function handle(): void
    {
        $article = Article::query()->find($this->articleId);
        $subscriber = NewsletterSubscriber::query()->find($this->subscriberId);

        if (! $article || ! $subscriber || ! $subscriber->isSubscribed()) {
            return;
        }

        if ($article->status !== 'published' || ! $article->published_at) {
            return;
        }

        Mail::to($subscriber->email)->send(new NewArticlePublishedMail($article, $subscriber));
    }

    public function failed(?Throwable $exception): void
    {
        Log::warning('Queued newsletter article email failed.', [
            'article_id' => $this->articleId,
            'subscriber_id' => $this->subscriberId,
            'message' => $exception?->getMessage(),
        ]);
    }
}
