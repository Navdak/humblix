@extends('layouts.admin')
@section('title','Edit SEO Settings')
@section('page_title','Edit SEO: '.$setting->page_label)
@section('page_subtitle','Update metadata safely. Page keys are fixed to avoid breaking public route mapping.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.seo-settings.index') }}">Back to SEO Settings</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.seo-settings.update', $setting) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="admin-list-intro"><strong>{{ $setting->page_key }}</strong><span>Public page metadata</span></div>
    <div class="form-grid">
        <div class="form-field"><label>Page label</label><input name="page_label" value="{{ old('page_label', $setting->page_label) }}" required maxlength="120"></div>
        <div class="form-field"><label>Meta title</label><input name="meta_title" value="{{ old('meta_title', $setting->meta_title) }}" maxlength="70"></div>
        <div class="form-field" style="grid-column:1/-1"><label>Meta description</label><textarea name="meta_description" maxlength="170" rows="3">{{ old('meta_description', $setting->meta_description) }}</textarea></div>
        <div class="form-field" style="grid-column:1/-1"><label>Meta keywords <small>(optional)</small></label><input name="meta_keywords" value="{{ old('meta_keywords', $setting->meta_keywords) }}" maxlength="255"></div>
        <div class="form-field" style="grid-column:1/-1"><label>Canonical URL <small>(optional)</small></label><input name="canonical_url" value="{{ old('canonical_url', $setting->canonical_url) }}" maxlength="255"></div>
    </div>

    <h3 style="margin-top:22px">Open Graph</h3>
    <div class="form-grid">
        <div class="form-field"><label>OG title</label><input name="og_title" value="{{ old('og_title', $setting->og_title) }}" maxlength="70"></div>
        <div class="form-field"><label>OG image</label><input type="file" name="og_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">@if($setting->ogImageUrl())<small><a href="{{ $setting->ogImageUrl() }}" target="_blank" rel="noopener">Current image</a></small>@endif</div>
        <div class="form-field" style="grid-column:1/-1"><label>OG description</label><textarea name="og_description" maxlength="200" rows="3">{{ old('og_description', $setting->og_description) }}</textarea></div>
    </div>

    <h3 style="margin-top:22px">Twitter Card</h3>
    <div class="form-grid">
        <div class="form-field"><label>Twitter title</label><input name="twitter_title" value="{{ old('twitter_title', $setting->twitter_title) }}" maxlength="70"></div>
        <div class="form-field"><label>Twitter image</label><input type="file" name="twitter_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">@if($setting->twitterImageUrl())<small><a href="{{ $setting->twitterImageUrl() }}" target="_blank" rel="noopener">Current image</a></small>@endif</div>
        <div class="form-field" style="grid-column:1/-1"><label>Twitter description</label><textarea name="twitter_description" maxlength="200" rows="3">{{ old('twitter_description', $setting->twitter_description) }}</textarea></div>
    </div>

    <h3 style="margin-top:22px">Robots and structured data</h3>
    <div class="form-grid">
        <label style="display:flex;align-items:center;gap:8px;margin-top:26px"><input type="checkbox" name="noindex" value="1" @checked(old('noindex', $setting->noindex)) style="width:auto"> Noindex this page</label>
        <label style="display:flex;align-items:center;gap:8px;margin-top:26px"><input type="checkbox" name="nofollow" value="1" @checked(old('nofollow', $setting->nofollow)) style="width:auto"> Nofollow links on this page</label>
        <div class="form-field" style="grid-column:1/-1"><label>Structured data JSON <small>(optional, valid JSON only)</small></label><textarea name="structured_data_json" rows="8" spellcheck="false">{{ old('structured_data_json', $setting->structured_data_json ? json_encode($setting->structured_data_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '') }}</textarea></div>
    </div>
    <div class="admin-note" style="margin-top:14px">Do not add fake ratings, prices, offers, certifications or legal claims. Product offer schema is intentionally avoided unless real pricing/availability data exists.</div>
    <button class="btn btn-primary" style="margin-top:20px">Save SEO Settings</button>
</form>
@endsection
