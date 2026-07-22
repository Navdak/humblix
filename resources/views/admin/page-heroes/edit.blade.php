@extends('layouts.admin')
@section('title','Edit Page Hero')
@section('page_title','Edit Page Hero')
@section('page_subtitle','Update the public banner for '.$hero->label.'.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.page-heroes.index') }}">Back to Page Heroes</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.page-heroes.update', $hero) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="form-grid">
        <div class="form-field">
            <label>Page Key</label>
            <input type="text" value="{{ $hero->key }}" disabled>
            <small>This stable key connects the admin record to the public page.</small>
        </div>
        <div class="form-field">
            <label>Admin Label</label>
            <input type="text" name="label" value="{{ old('label', $hero->label) }}" required>
        </div>
        <div class="form-field">
            <label>Eyebrow / Small Label</label>
            <input type="text" name="eyebrow" value="{{ old('eyebrow', $hero->eyebrow) }}">
        </div>
        <div class="form-field">
            <label>Status</label>
            <label style="display:flex;align-items:center;gap:8px;margin-top:14px">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $hero->is_active)) style="width:auto">
                Active on website
            </label>
        </div>
        <div class="form-field full">
            <label>Hero Title</label>
            <input type="text" name="title" value="{{ old('title', $hero->title) }}" required>
        </div>
        <div class="form-field full">
            <label>Hero Subtitle / Description</label>
            <textarea name="subtitle" rows="4">{{ old('subtitle', $hero->subtitle) }}</textarea>
        </div>
        <div class="form-field full">
            <label>Hero Image</label>
            @if($hero->imageUrl())
                <div class="image-frame" style="max-width:360px;aspect-ratio:16/9;margin-bottom:12px">
                    <img loading="lazy" decoding="async" width="960" height="410" src="{{ $hero->imageUrl() }}" alt="{{ $hero->label }} current hero image">
                </div>
            @endif
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            <small>Upload from this device. JPG, PNG or WebP only, max 6MB. If empty, the current image stays.</small>
            @if($hero->hasUploadedImage())
                <label style="display:flex;align-items:center;gap:8px;margin-top:12px">
                    <input type="checkbox" name="remove_image" value="1" style="width:auto">
                    Remove uploaded image and use generated fallback
                </label>
            @else
                <small>Current image is the generated fallback: {{ $hero->fallback_image_path ?: 'none set' }}</small>
            @endif
        </div>
    </div>
    <div class="admin-actions" style="margin-top:22px">
        <button class="btn btn-primary" type="submit">Save Page Hero</button>
        <a class="btn btn-outline" href="{{ route('admin.page-heroes.index') }}">Cancel</a>
    </div>
</form>
@endsection
