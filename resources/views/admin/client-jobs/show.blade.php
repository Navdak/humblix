@extends('layouts.admin')
@section('title','Client Job')
@section('page_title','Client Job Portal')
@section('page_subtitle','Manage private client communication, job status and internal commercial documentation.')
@section('page_actions')
    <a class="btn btn-outline" href="{{ route('admin.client-jobs.index') }}">Back to Client Jobs</a>
@endsection
@section('content')
@php
    $enquiry = $clientJob->enquiry;
    $portalUrl = $clientJob->portalUrl();
    $lastMessageId = $clientJob->messages->max('id') ?? 0;
@endphp
<div class="grid grid-2">
    <section class="admin-card">
        <span class="badge">{{ $clientJob->job_reference }}</span>
        <h2 style="margin-top:12px">{{ $clientJob->clientName() }}</h2>
        <p class="section-sub">{{ $enquiry?->display_type_of_work ?: 'Client job' }}{{ $enquiry?->display_location ? ' - '.$enquiry->display_location : '' }}</p>

        <dl class="system-list" style="margin-top:18px">
            <div><dt>Enquiry Reference</dt><dd><a href="{{ route('admin.enquiries.show', $enquiry) }}">{{ $enquiry?->reference_number }}</a></dd></div>
            <div><dt>Contact</dt><dd>{{ $clientJob->clientContact() ?: 'No contact saved' }}</dd></div>
            <div><dt>Assigned Engineer</dt><dd>{{ $clientJob->assignedEngineer?->assignmentLabel() ?: ($enquiry?->assignedEngineerLabel() ?: 'Unassigned') }}</dd></div>
            <div><dt>Site Address</dt><dd>{{ $enquiry?->display_site_address ?: 'Not confirmed yet' }}</dd></div>
            <div><dt>Portal Status</dt><dd>{{ $clientJob->portal_enabled ? 'Enabled' : 'Disabled' }}</dd></div>
        </dl>

        <hr style="border:0;border-top:1px solid var(--line);margin:22px 0">
        <h3>Private Client Link</h3>
        <p class="section-sub">Keep this link private. Regenerate it if it is shared with the wrong person.</p>
        <div class="copy-field" style="margin-top:12px">
            <input value="{{ $portalUrl }}" readonly data-copy-source>
            <button type="button" class="btn btn-white" data-copy-button>Copy</button>
        </div>
        <form method="POST" action="{{ route('admin.client-jobs.regenerate-token', $clientJob) }}" onsubmit="return confirm('Regenerate this private client link? The old link will stop working.')" style="margin-top:12px">
            @csrf @method('PATCH')
            <button class="btn btn-outline" type="submit">Regenerate Client Link</button>
        </form>
    </section>

    <form class="admin-card" method="POST" action="{{ route('admin.client-jobs.update', $clientJob) }}">
        @csrf @method('PUT')
        <h2>Job Management</h2>
        <div class="form-grid">
            <div class="form-field">
                <label>Job Status</label>
                <select name="status">
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $clientJob->status) === $status)>{{ ucwords(str_replace('_',' ', $status)) }}</option>
                    @endforeach
                </select>
            </div>
            <label class="admin-note" style="display:flex;align-items:flex-start;gap:10px;margin-top:26px">
                <input type="checkbox" name="portal_enabled" value="1" @checked(old('portal_enabled', $clientJob->portal_enabled)) style="width:auto;margin-top:4px">
                <span><strong>Client portal enabled</strong><br><small>Disable when the job is closed or the link should stop working.</small></span>
            </label>
        </div>

        <hr style="border:0;border-top:1px solid var(--line);margin:22px 0">
        <h3>Commercial Agreement</h3>
        @if($canManageCommercialAgreement)
            <p class="section-sub">Private admin-only documentation. This is not shown to the client portal yet.</p>
            <div class="form-grid">
                <div class="form-field">
                    <label>Agreed Amount</label>
                    <input type="number" name="agreed_amount" min="0" step="0.01" value="{{ old('agreed_amount', $clientJob->agreed_amount) }}" placeholder="0.00">
                </div>
                <div class="form-field">
                    <label>Currency</label>
                    <input name="currency" value="{{ old('currency', $clientJob->currency ?: 'NGN') }}" maxlength="10">
                </div>
                <div class="form-field">
                    <label>Payment Status</label>
                    <select name="payment_status">
                        @foreach($paymentStatuses as $status)
                            <option value="{{ $status }}" @selected(old('payment_status', $clientJob->payment_status) === $status)>{{ ucwords(str_replace('_',' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label>Date Agreed</label>
                    <input type="date" name="agreed_at" value="{{ old('agreed_at', $clientJob->agreed_at?->format('Y-m-d')) }}">
                </div>
                <div class="form-field full">
                    <label>Agreement Note</label>
                    <textarea name="agreement_note" rows="4" placeholder="Private note about what was agreed with the client.">{{ old('agreement_note', $clientJob->agreement_note) }}</textarea>
                </div>
            </div>
        @else
            <dl class="system-list">
                <div><dt>Agreed Amount</dt><dd>{{ $clientJob->formattedAmount() }}</dd></div>
                <div><dt>Payment Status</dt><dd>{{ $clientJob->paymentStatusLabel() }}</dd></div>
                <div><dt>Date Agreed</dt><dd>{{ $clientJob->agreed_at?->format('M d, Y') ?: 'Not recorded' }}</dd></div>
            </dl>
            <p class="section-sub">You can view the job, but you do not have permission to edit private commercial details.</p>
        @endif

        <button class="btn btn-primary" style="margin-top:18px">Save Job Details</button>
    </form>
</div>

<div class="grid grid-2" style="margin-top:22px">
    <section class="admin-card job-conversation-card">
        <div class="admin-list-intro">
            <strong>Conversation Thread</strong>
            <span>Client-visible messages and internal notes are saved here.</span>
        </div>
        <div class="job-message-thread" data-job-thread data-messages-endpoint="{{ route('admin.client-jobs.messages.index', $clientJob) }}" data-last-message-id="{{ $lastMessageId }}">
            @forelse($clientJob->messages as $message)
                <article class="job-message job-message-{{ $message->sender_type }} {{ $message->visibility === 'internal' ? 'is-internal' : '' }}" data-message-id="{{ $message->id }}">
                    <div><strong>{{ $message->senderLabel() }}</strong><small>{{ $message->visibility === 'internal' ? 'Internal note - ' : '' }}{{ $message->created_at->diffForHumans() }}</small></div>
                    <p>{{ $message->body }}</p>
                    @include('client-jobs._message-attachments', ['message' => $message, 'clientJob' => $clientJob, 'context' => 'admin'])
                </article>
            @empty
                <div class="admin-empty"><span><x-admin-icon name="chat"/></span><strong>No messages yet</strong><p>Send the first client-visible update or add an internal note.</p></div>
            @endforelse
        </div>
    </section>

    <form class="admin-card" method="POST" action="{{ route('admin.client-jobs.messages.store', $clientJob) }}" enctype="multipart/form-data" data-job-message-form>
        @csrf
        <h2>Send Message / Note</h2>
        <div class="alert" data-job-message-status hidden></div>
        <div class="form-field">
            <label>Visibility</label>
            <select name="visibility">
                <option value="client">Client-visible message</option>
                <option value="internal">Internal admin note</option>
            </select>
            <small>Internal notes are not shown in the client portal or email.</small>
        </div>
        <div class="form-field" style="margin-top:14px">
            <label>Message</label>
            <textarea name="body" rows="7" placeholder="Write a clear job update, question, or internal note.">{{ old('body') }}</textarea>
            <small>You can send a message, attach files, or do both.</small>
        </div>
        <div class="form-field" style="margin-top:14px">
            <label>Optional Attachments</label>
            <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">
            <small>Max 3 files, 10MB each. Images and PDF/Word documents only. Do not upload videos here.</small>
        </div>
        @if($enquiry?->email)
            <label class="admin-note" style="display:flex;align-items:flex-start;gap:10px;margin-top:14px">
                <input type="checkbox" name="send_client_email" value="1" style="width:auto;margin-top:4px">
                <span><strong>Email client about this message</strong><br><small>The email includes the private portal link. Use only for client-visible messages.</small></span>
            </label>
        @endif
        <button class="btn btn-primary" style="margin-top:18px" data-job-message-submit>Save Message</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-copy-button]').forEach((button) => {
    button.addEventListener('click', async () => {
        const input = button.closest('.copy-field')?.querySelector('[data-copy-source]');
        if (!input) return;
        input.select();
        try {
            await navigator.clipboard.writeText(input.value);
            button.textContent = 'Copied';
            window.setTimeout(() => button.textContent = 'Copy', 1800);
        } catch (error) {
            document.execCommand('copy');
        }
    });
});

(() => {
    const thread = document.querySelector('[data-job-thread]');
    if (!thread) return;

    const form = document.querySelector('[data-job-message-form]');
    const submitButton = document.querySelector('[data-job-message-submit]');
    const statusBox = document.querySelector('[data-job-message-status]');
    let lastId = Number(thread.dataset.lastMessageId || 0);
    let timer;

    const escapeHtml = (value) => String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    const showStatus = (message, type = 'success') => {
        if (!statusBox) return;
        statusBox.textContent = message || '';
        statusBox.className = `alert alert-${type}`;
        statusBox.hidden = !message;
    };
    const scrollThread = () => {
        thread.scrollTop = thread.scrollHeight;
    };
    const renderMessage = (message) => {
        if (thread.querySelector(`[data-message-id="${message.id}"]`)) return;
        thread.querySelector('.admin-empty')?.remove();
        const item = document.createElement('article');
        item.className = `job-message job-message-${message.sender_type} ${message.visibility === 'internal' ? 'is-internal' : ''}`;
        item.dataset.messageId = message.id;
        const attachments = (message.attachments || []).map((attachment) => {
            if (attachment.is_image) {
                return `<a class="job-attachment is-image" href="${escapeHtml(attachment.url)}" target="_blank" rel="noopener"><img src="${escapeHtml(attachment.url)}" alt="${escapeHtml(attachment.name)}" loading="lazy" decoding="async"><span><strong>${escapeHtml(attachment.name)}</strong><small>${escapeHtml(attachment.size)}</small></span></a>`;
            }

            return `<a class="job-attachment is-file" href="${escapeHtml(attachment.url)}" target="_blank" rel="noopener"><span class="job-attachment-icon" aria-hidden="true">&#128206;</span><span><strong>${escapeHtml(attachment.name)}</strong><small>${escapeHtml(attachment.size)}</small></span></a>`;
        }).join('');
        item.innerHTML = `<div><strong>${escapeHtml(message.sender_name)}</strong><small>${message.visibility === 'internal' ? 'Internal note - ' : ''}${escapeHtml(message.human_time || '')}</small></div><p>${escapeHtml(message.body)}</p>${attachments ? `<div class="job-attachments">${attachments}</div>` : ''}`;
        thread.append(item);
        lastId = Math.max(lastId, Number(message.id));
        scrollThread();
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

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        showStatus('');
        const defaultLabel = submitButton?.textContent || 'Save Message';
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            });
            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                const errors = data.errors ? Object.values(data.errors).flat().join(' ') : '';
                throw new Error(errors || data.message || 'Message could not be saved. Please try again.');
            }

            if (data.message) {
                renderMessage(data.message);
            }

            form.reset();
            showStatus(data.notice || 'Message saved.', data.email_failed ? 'error' : 'success');
        } catch (error) {
            showStatus(error.message || 'Message could not be saved. Please try again.', 'error');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = defaultLabel;
            }
        }
    });

    document.addEventListener('visibilitychange', () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(poll, document.hidden ? 60000 : 1000);
    });
    scrollThread();
    timer = window.setTimeout(poll, 10000);
})();
</script>
@endpush
