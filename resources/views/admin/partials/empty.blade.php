<div class="admin-empty">
    <span><x-admin-icon name="{{ $icon ?? 'clock' }}"/></span>
    <strong>{{ $title ?? 'Nothing here yet' }}</strong>
    <p>{{ $message ?? 'New records will appear here.' }}</p>
    @isset($actionUrl)
        <a class="btn btn-primary" href="{{ $actionUrl }}">{{ $actionLabel ?? 'Add record' }}</a>
    @endisset
</div>
