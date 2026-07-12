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
    <button class="btn btn-primary" style="margin-top:20px">Save Settings</button>
</form>
@endsection
