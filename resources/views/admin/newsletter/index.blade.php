@extends('layouts.admin')
@section('title','Newsletter Subscribers')
@section('page_title','Newsletter Subscribers')
@section('page_subtitle','Manage confirmed, pending and unsubscribed HUMELIX LIMITED resource subscribers.')
@section('content')
<div class="grid grid-4" style="margin-bottom:16px">
    <div class="admin-kpi"><span>Total</span><strong>{{ number_format($totalSubscribers) }}</strong><small>All newsletter records</small></div>
    <div class="admin-kpi"><span>Subscribed</span><strong>{{ number_format($confirmedSubscribers) }}</strong><small>Will receive new articles</small></div>
    <div class="admin-kpi"><span>Pending</span><strong>{{ number_format($pendingSubscribers) }}</strong><small>Awaiting email confirmation</small></div>
    <div class="admin-kpi"><span>Unsubscribed</span><strong>{{ number_format($unsubscribedSubscribers) }}</strong><small>Opted out</small></div>
</div>

@if($canManageCompanyWebsiteUrl)
    <form class="admin-card" method="POST" action="{{ route('admin.newsletter.company-url') }}" style="margin-bottom:16px">
        @csrf @method('PATCH')
        <div class="admin-list-intro">
            <div>
                <strong>Company website URL for newsletter emails</strong>
                <span>This link is used inside branded welcome and article emails. Keep it as the official company website URL.</span>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-field">
                <label>Company Website URL</label>
                <input type="url" name="company_website_url" value="{{ old('company_website_url', $companyWebsiteUrl) }}" placeholder="https://humelix.com" required>
                <small>Enter only the main website URL, for example https://humelix.com. Article emails will automatically open the correct /resources/article-slug page.</small>
            </div>
            <div class="form-field" style="justify-content:end">
                <button class="btn btn-primary" type="submit">Save Website URL</button>
            </div>
        </div>
    </form>
@endif

<div class="admin-card">
    <div class="admin-list-intro">
        <div>
            <strong>Subscriber list</strong>
            <span>Only confirmed subscribers receive automatic new-article emails.</span>
        </div>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Subscriber</th><th>Status</th><th>Confirmed</th><th>Last Updated</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($subscribers as $subscriber)
                <tr>
                    <td data-label="Subscriber">
                        <strong>{{ $subscriber->name ?: 'Newsletter reader' }}</strong>
                        <small>{{ $subscriber->email }}</small>
                    </td>
                    <td data-label="Status"><span class="badge">{{ ucwords(str_replace('_',' ', $subscriber->status)) }}</span></td>
                    <td data-label="Confirmed">{{ $subscriber->confirmed_at?->format('M j, Y g:ia') ?: '—' }}</td>
                    <td data-label="Updated">{{ $subscriber->updated_at?->format('M j, Y g:ia') ?: '—' }}</td>
                    <td data-label="Actions" class="admin-actions">
                        @if($subscriber->isSubscribed())
                            <form method="POST" action="{{ route('admin.newsletter.unsubscribe', $subscriber) }}">@csrf @method('PATCH')<button class="btn btn-outline" type="submit">Unsubscribe</button></form>
                        @else
                            <form method="POST" action="{{ route('admin.newsletter.resubscribe', $subscriber) }}">@csrf @method('PATCH')<button class="btn btn-white" type="submit">Mark Subscribed</button></form>
                        @endif
                        @if(auth()->user()?->canDeleteRecords())<form method="POST" action="{{ route('admin.newsletter.destroy', $subscriber) }}" onsubmit="return confirm('Delete this subscriber record?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c" type="submit">Delete</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">@include('admin.partials.empty',['title'=>'No newsletter subscribers yet','message'=>'Subscribers will appear here after visitors join from the Resources page.','icon'=>'articles'])</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:18px">{{ $subscribers->links() }}</div>
</div>
@endsection
