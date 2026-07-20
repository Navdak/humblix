<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcomeMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'newsletter_consent' => ['accepted'],
        ], [
            'newsletter_consent.accepted' => 'Please confirm you want to receive HUMELIX LIMITED resource updates.',
        ]);

        $subscriber = NewsletterSubscriber::firstOrNew(['email' => strtolower($data['email'])]);

        if ($subscriber->exists && $subscriber->isSubscribed()) {
            return back()->with('success', 'You are already subscribed to HUMELIX LIMITED resource updates.');
        }

        $subscriber->fill([
            'name' => $data['name'] ?? $subscriber->name,
            'status' => NewsletterSubscriber::STATUS_SUBSCRIBED,
            'confirmed_at' => now(),
            'unsubscribed_at' => null,
        ]);
        $subscriber->unsubscribe_token = $subscriber->unsubscribe_token ?: \Illuminate\Support\Str::random(48);
        $subscriber->confirmation_token = null;
        $subscriber->save();

        try {
            Mail::to($subscriber->email)->send(new NewsletterWelcomeMail($subscriber));
        } catch (Throwable $exception) {
            Log::warning('Newsletter welcome email failed.', [
                'subscriber_id' => $subscriber->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('success', 'You are subscribed to HUMELIX LIMITED resource updates. The welcome email may be delayed.');
        }

        return back()->with('success', 'You are subscribed to HUMELIX LIMITED resource updates.');
    }

    public function confirm(string $token): View|RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)->first();

        if (! $subscriber) {
            return redirect()->route('articles.index')->with('success', 'This newsletter confirmation link is no longer valid.');
        }

        $subscriber->markSubscribed();

        return view('newsletter.status', [
            'title' => 'Newsletter subscription confirmed',
            'message' => 'You will now receive new HUMELIX LIMITED resources and engineering updates.',
            'actionLabel' => 'Explore Resources',
            'actionUrl' => route('articles.index'),
        ]);
    }

    public function unsubscribe(string $token): View|RedirectResponse
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (! $subscriber) {
            return redirect()->route('home')->with('success', 'This unsubscribe link is no longer valid.');
        }

        $subscriber->markUnsubscribed();

        return view('newsletter.status', [
            'title' => 'You have been unsubscribed',
            'message' => 'You will no longer receive HUMELIX LIMITED newsletter emails. You can subscribe again anytime from the Resources page.',
            'actionLabel' => 'Back to HUMELIX',
            'actionUrl' => route('home'),
        ]);
    }
}
