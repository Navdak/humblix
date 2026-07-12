@extends('layouts.admin')
@section('title','SEO Settings')
@section('page_title','SEO Settings')
@section('page_subtitle','Manage metadata, social previews, robots directives and safe structured data for key public pages.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('seo.sitemap') }}" target="_blank" rel="noopener"><x-admin-icon name="external"/> View Sitemap</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Public metadata directory</strong><span>{{ $settings->count() }} managed page keys.</span></div>
    <table class="admin-table">
        <thead><tr><th>Page</th><th>Meta title</th><th>Robots</th><th>Updated</th><th></th></tr></thead>
        <tbody>
        @forelse($settings as $setting)
            <tr>
                <td data-label="Page"><strong>{{ $setting->page_label }}</strong><small>{{ $setting->page_key }}</small></td>
                <td data-label="Meta title">{{ $setting->meta_title ?: 'Using fallback title' }}<small>{{ \Illuminate\Support\Str::limit($setting->meta_description ?: 'Using fallback description', 90) }}</small></td>
                <td data-label="Index status"><span class="badge">{{ $setting->noindex ? 'noindex' : 'index' }} / {{ $setting->nofollow ? 'nofollow' : 'follow' }}</span></td>
                <td data-label="Updated">{{ $setting->updated_at?->format('M j, Y H:i') }}</td>
                <td data-label="Actions" class="admin-actions"><a class="btn btn-white" href="{{ route('admin.seo-settings.edit', $setting) }}">Edit</a></td>
            </tr>
        @empty
            <tr><td colspan="5">@include('admin.partials.empty',['title'=>'No SEO settings yet','message'=>'Run migrations to create the SEO settings table.','icon'=>'settings'])</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
