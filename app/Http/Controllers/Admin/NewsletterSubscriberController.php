<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\SiteSetting;
use App\Support\HumelixLinks;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsletterSubscriberController extends Controller
{
    public function index(): View
    {
        return view('admin.newsletter.index', [
            'subscribers' => NewsletterSubscriber::latest()->paginate(20),
            'totalSubscribers' => NewsletterSubscriber::count(),
            'confirmedSubscribers' => NewsletterSubscriber::subscribed()->count(),
            'pendingSubscribers' => NewsletterSubscriber::where('status', NewsletterSubscriber::STATUS_PENDING)->count(),
            'unsubscribedSubscribers' => NewsletterSubscriber::where('status', NewsletterSubscriber::STATUS_UNSUBSCRIBED)->count(),
            'companyWebsiteUrl' => SiteSetting::query()->where('key', 'company_website_url')->value('value') ?: HumelixLinks::websiteUrl(),
            'canManageCompanyWebsiteUrl' => auth()->user()?->isSuperAdmin() || auth()->user()?->hasRole('company_owner'),
        ]);
    }

    public function updateCompanyWebsiteUrl(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->isSuperAdmin() || $request->user()?->hasRole('company_owner'), 403);

        if ($request->filled('company_website_url')) {
            $request->merge([
                'company_website_url' => HumelixLinks::normalizeWebsiteUrl((string) $request->input('company_website_url')),
            ]);
        }

        $data = $request->validate([
            'company_website_url' => ['required', 'url', 'max:255'],
        ]);

        SiteSetting::setValue('company_website_url', $data['company_website_url'], 'newsletter', 'url');

        return back()->with('success', 'Company website URL updated for newsletter emails.');
    }

    public function unsubscribe(NewsletterSubscriber $newsletterSubscriber): RedirectResponse
    {
        $newsletterSubscriber->markUnsubscribed();

        return back()->with('success', 'Subscriber marked as unsubscribed.');
    }

    public function resubscribe(NewsletterSubscriber $newsletterSubscriber): RedirectResponse
    {
        $newsletterSubscriber->markSubscribed();

        return back()->with('success', 'Subscriber restored as subscribed.');
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber): RedirectResponse
    {
        $newsletterSubscriber->delete();

        return back()->with('success', 'Subscriber deleted.');
    }
}
