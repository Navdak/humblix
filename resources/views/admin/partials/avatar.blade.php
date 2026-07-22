@props(['user', 'size' => 'default'])

@php
    $avatarUser = $user ?? null;
    $avatarUrl = $avatarUser?->avatarUrl();
    $avatarName = $avatarUser?->displayName() ?? 'Admin User';
@endphp

<span class="admin-avatar admin-avatar-{{ $size }} {{ $avatarUrl ? 'has-image' : '' }}" aria-label="{{ $avatarName }}">
    @if($avatarUrl)
        <img loading="lazy" decoding="async" width="48" height="48" src="{{ $avatarUrl }}" alt="{{ $avatarName }}">
    @else
        {{ $avatarUser?->avatarInitial() ?? 'A' }}
    @endif
</span>
