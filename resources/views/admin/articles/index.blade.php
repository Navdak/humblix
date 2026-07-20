@extends('layouts.admin')
@section('title','Articles')
@section('page_title','Articles / Blog')
@section('page_subtitle','Publish HVAC, solar, electrical, safety, maintenance, vendor, and company resources.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.articles.create') }}"><x-admin-icon name="plus"/> New Article</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Content library</strong><span>Manage drafts and published resources.</span></div>
    <table class="admin-table">
        <thead>
            <tr><th>Article</th><th>Category</th><th>Status</th><th>Published</th><th></th></tr>
        </thead>
        <tbody>
            @forelse($articles as $article)
                <tr>
                    <td data-label="Article">
                        <strong>{{ $article->title }}</strong><br>
                        <span class="meta">/{{ $article->slug }}</span>
                    </td>
                    <td data-label="Category"><span class="badge">{{ $article->categoryLabel() }}</span></td>
                    <td data-label="Status"><span class="badge">{{ $article->status }}</span></td>
                    <td data-label="Published">{{ optional($article->published_at)->format('M d, Y') ?: '—' }}</td>
                    <td data-label="Actions" class="admin-actions">
                        <a class="btn btn-white" href="{{ route('admin.articles.edit',$article) }}">Edit</a>
                        @if(auth()->user()?->canDeleteRecords())
                            <form method="POST" action="{{ route('admin.articles.destroy',$article) }}" onsubmit="return confirm('Delete this article?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline" style="color:#b91c1c">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">@include('admin.partials.empty',['title'=>'No resources yet','message'=>'Create your first Humelix resource when ready.','actionUrl'=>route('admin.articles.create'),'actionLabel'=>'New Article','icon'=>'articles'])</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:18px">{{ $articles->links() }}</div>
</div>
@endsection
