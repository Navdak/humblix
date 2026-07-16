@extends('layouts.admin')
@section('title','Site Settings')
@section('page_title','Site Settings')
@section('page_subtitle','Update public brand copy, contact details, and social profiles.')
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    <div class="section-head left" style="margin-bottom:24px"><span class="eyebrow">Homepage Copy</span><h2 class="section-title" style="font-size:32px">Edit public brand content without touching code.</h2></div>
    <div class="form-grid">
        @foreach($fields as $key => $type)
            <div class="form-field {{ str_contains($key, 'headline') || str_contains($key, 'subtext') || str_contains($key, 'snapshot') ? 'full' : '' }}">
                <label>{{ ucwords(str_replace('_',' ', $key)) }}</label>
                @if($type === 'textarea')
                    <textarea name="{{ $key }}" rows="{{ $key === 'founder_snapshot' ? 5 : 3 }}">{{ old($key, $settings[$key] ?? '') }}</textarea>
                @else
                    <input type="{{ $type }}" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}">
                @endif
            </div>
        @endforeach
    </div>
    @if(!empty($developerFields))
        <div class="admin-note" style="margin-top:22px">
            <strong>Developer credit:</strong> These fields are visible to Technical Super Admin only and control the subtle public/admin credit for the website developer.
        </div>
        <div class="form-grid" style="margin-top:16px">
            <label class="form-field full" style="display:flex;align-items:center;flex-direction:row;gap:10px">
                <input type="checkbox" name="developer_credit_enabled" value="1" @checked(old('developer_credit_enabled', $settings['developer_credit_enabled'] ?? '1') !== '0') style="width:auto">
                <span>Show developer credit in the website footer and admin login</span>
            </label>
            <div class="form-field">
                <label>Developer Credit Label</label>
                <input type="text" name="developer_credit_label" value="{{ old('developer_credit_label', $settings['developer_credit_label'] ?? 'Navdak Digital') }}">
            </div>
            <div class="form-field">
                <label>Developer Portfolio URL</label>
                <input type="url" name="developer_credit_url" value="{{ old('developer_credit_url', $settings['developer_credit_url'] ?? '') }}" placeholder="https://your-portfolio.example">
            </div>
        </div>
    @endif
    <button class="btn btn-primary" style="margin-top:20px">Save Settings</button>
</form>
@endsection
