@extends('layouts.admin')
@section('title','Page Heroes')
@section('page_title','Page Heroes')
@section('page_subtitle','Edit public page banner text and hero images without touching code.')
@section('content')
<div class="admin-card">
    <div class="admin-list-intro">
        <strong>Public page banners</strong>
        <span>Developer and CEO-level content admins can update these page headers. Uploaded images replace previous uploaded images safely.</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Page</th>
                    <th>Hero Title</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($heroes as $hero)
                    <tr>
                        <td data-label="Page">
                            <strong>{{ $hero->label }}</strong>
                            <br><span class="meta">{{ $hero->key }}</span>
                        </td>
                        <td data-label="Hero Title">
                            <strong>{{ $hero->title }}</strong>
                            @if($hero->eyebrow)<br><span class="meta">{{ $hero->eyebrow }}</span>@endif
                        </td>
                        <td data-label="Image">
                            <span class="badge">{{ $hero->hasUploadedImage() ? 'Uploaded' : 'Generated fallback' }}</span>
                        </td>
                        <td data-label="Status"><span class="badge">{{ $hero->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td data-label="Actions" class="admin-actions">
                            <a class="btn btn-white" href="{{ route('admin.page-heroes.edit', $hero) }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">@include('admin.partials.empty',['title'=>'No page heroes yet','message'=>'Run migrations/seeders to create editable page hero records.'])</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
