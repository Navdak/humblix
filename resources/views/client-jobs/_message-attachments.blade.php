@php
    $attachments = $message->attachments ?? collect();
    $context = $context ?? 'client';
@endphp

@if($attachments->isNotEmpty())
    <div class="job-attachments">
        @foreach($attachments as $attachment)
            @php
                $attachmentUrl = $context === 'admin'
                    ? route('admin.client-jobs.attachments.show', [$clientJob, $attachment])
                    : route('client-jobs.attachments.show', [$clientJob->portal_token, $attachment]);
            @endphp
            <a class="job-attachment {{ $attachment->isImage() ? 'is-image' : 'is-file' }}" href="{{ $attachmentUrl }}" target="_blank" rel="noopener">
                @if($attachment->isImage())
                    <img src="{{ $attachmentUrl }}" alt="{{ $attachment->original_name }}" loading="lazy" decoding="async">
                @else
                    <span class="job-attachment-icon" aria-hidden="true">📎</span>
                @endif
                <span>
                    <strong>{{ $attachment->original_name }}</strong>
                    <small>{{ $attachment->formattedSize() }}</small>
                </span>
            </a>
        @endforeach
    </div>
@endif
