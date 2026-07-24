@extends('layouts.app')
@section('title', $clientJob->job_reference.' Job Portal')
@section('content')
@php
    $enquiry = $clientJob->enquiry;
    $lastMessageId = $messages->max('id') ?? 0;
@endphp
<x-page-hero
    eyebrow="Private Client Portal"
    :title="$clientJob->job_reference"
    :subtitle="'Track job updates and communicate with HUMELIX LIMITED while work is ongoing.'"
    image="images/generated/careers/careers-office-admin-culture.jpg"
/>

<section class="section">
    <div class="container">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())
            <div class="alert alert-error">
                <strong>Fix these fields:</strong>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid grid-2">
            <aside class="card">
                <span class="eyebrow">Job Summary</span>
                <h2>{{ $clientJob->statusLabel() }}</h2>
                <p class="section-sub">This portal is private to your HUMELIX job. Keep the link safe and do not share it publicly.</p>
                <dl class="system-list" style="margin-top:18px">
                    <div><dt>Reference</dt><dd>{{ $clientJob->job_reference }}</dd></div>
                    <div><dt>Service</dt><dd>{{ $enquiry?->display_type_of_work ?: 'Service request' }}</dd></div>
                    <div><dt>Location</dt><dd>{{ $enquiry?->display_location ?: 'Location under review' }}</dd></div>
                    <div><dt>Preferred Contact</dt><dd>{{ $enquiry?->preferred_contact ?: 'Not specified' }}</dd></div>
                    <div><dt>Assigned Team</dt><dd>{{ $clientJob->assignedEngineer?->field_of_work ?: 'HUMELIX Operations' }}</dd></div>
                </dl>
            </aside>

            <div class="card">
                <span class="eyebrow">Message HUMELIX</span>
                <h2>Send an update or question.</h2>
                <form method="POST" action="{{ route('client-jobs.messages.store', $clientJob->portal_token) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="website" autocomplete="off" tabindex="-1" style="position:absolute;left:-9999px" aria-hidden="true">
                    <input type="hidden" name="form_started_at" value="{{ now()->timestamp }}">
                    <div class="form-field">
                        <label>Your Message</label>
                        <textarea name="body" rows="7" placeholder="Write your question, site update, access note, or progress feedback.">{{ old('body') }}</textarea>
                        <small>You can send a message, attach files, or do both.</small>
                    </div>
                    <div class="form-field" style="margin-top:14px">
                        <label>Optional Attachments</label>
                        <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">
                        <small>Max 3 files, 10MB each. Images and PDF/Word documents only. For video, please paste a link in the message.</small>
                    </div>
                    <button class="btn btn-primary" style="margin-top:16px">Send Message</button>
                </form>
            </div>
        </div>

        <section class="card job-conversation-card" style="margin-top:24px">
            <div class="section-head section-head-row">
                <div>
                    <span class="eyebrow">Conversation</span>
                    <h2 class="section-title">Job updates and messages.</h2>
                </div>
                <button class="btn btn-outline" type="button" onclick="window.location.reload()">Refresh</button>
            </div>
            <div class="job-message-thread" data-job-thread data-messages-endpoint="{{ route('client-jobs.messages.index', $clientJob->portal_token) }}" data-last-message-id="{{ $lastMessageId }}">
                @forelse($messages as $message)
                    <article class="job-message job-message-{{ $message->sender_type }}" data-message-id="{{ $message->id }}">
                        <div><strong>{{ $message->senderLabel() }}</strong><small>{{ $message->created_at->diffForHumans() }}</small></div>
                        <p>{{ $message->body }}</p>
                        @include('client-jobs._message-attachments', ['message' => $message, 'clientJob' => $clientJob, 'context' => 'client'])
                    </article>
                @empty
                    <div class="empty-state"><h3>No messages yet.</h3><p class="section-sub">HUMELIX will add updates here after your job conversation starts.</p></div>
                @endforelse
            </div>
        </section>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const thread = document.querySelector('[data-job-thread]');
    if (!thread) return;
    let lastId = Number(thread.dataset.lastMessageId || 0);
    let timer;
    const escapeHtml = (value) => String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    const renderMessage = (message) => {
        if (thread.querySelector(`[data-message-id="${message.id}"]`)) return;
        thread.querySelector('.empty-state')?.remove();
        const item = document.createElement('article');
        item.className = `job-message job-message-${message.sender_type}`;
        item.dataset.messageId = message.id;
        const attachments = (message.attachments || []).map((attachment) => {
            if (attachment.is_image) {
                return `<a class="job-attachment is-image" href="${escapeHtml(attachment.url)}" target="_blank" rel="noopener"><img src="${escapeHtml(attachment.url)}" alt="${escapeHtml(attachment.name)}" loading="lazy" decoding="async"><span><strong>${escapeHtml(attachment.name)}</strong><small>${escapeHtml(attachment.size)}</small></span></a>`;
            }

            return `<a class="job-attachment is-file" href="${escapeHtml(attachment.url)}" target="_blank" rel="noopener"><span class="job-attachment-icon" aria-hidden="true">📎</span><span><strong>${escapeHtml(attachment.name)}</strong><small>${escapeHtml(attachment.size)}</small></span></a>`;
        }).join('');
        item.innerHTML = `<div><strong>${escapeHtml(message.sender_name)}</strong><small>${escapeHtml(message.human_time || '')}</small></div><p>${escapeHtml(message.body)}</p>${attachments ? `<div class="job-attachments">${attachments}</div>` : ''}`;
        thread.append(item);
        lastId = Math.max(lastId, Number(message.id));
    };
    const interval = () => document.hidden ? 60000 : 10000;
    const poll = async () => {
        try {
            const url = new URL(thread.dataset.messagesEndpoint, window.location.origin);
            url.searchParams.set('after', String(lastId));
            const response = await fetch(url, {headers:{Accept:'application/json','X-Requested-With':'XMLHttpRequest'}});
            if (response.ok) {
                const data = await response.json();
                (data.messages || []).forEach(renderMessage);
            }
        } finally {
            window.clearTimeout(timer);
            timer = window.setTimeout(poll, interval());
        }
    };
    document.addEventListener('visibilitychange', () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(poll, document.hidden ? 60000 : 1000);
    });
    timer = window.setTimeout(poll, 10000);
})();
</script>
@endpush
