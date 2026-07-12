@extends('layouts.admin')
@section('title','Reviews')
@section('page_title','Review Moderation')
@section('page_subtitle','Approve client feedback and manage public responses.')
@section('content')
<div class="grid grid-2">
@forelse($reviews as $review)
    <div class="admin-card">
        <form method="POST" action="{{ route('admin.reviews.update',$review) }}">
            @csrf @method('PUT')
            <div class="stars">{{ str_repeat('★', $review->rating) }}</div>
            <h2>{{ $review->client_name }}</h2>
            <p class="meta">{{ $review->client_role }} · {{ $review->company }} · {{ $review->location }}</p>
            <p class="section-sub">“{{ $review->comment }}”</p>
            <label style="display:flex;align-items:center;gap:8px;margin:14px 0"><input type="checkbox" name="is_approved" value="1" @checked($review->is_approved) style="width:auto"> Approved for public display</label>
            <div class="form-field"><label>Admin Response</label><textarea name="admin_response" rows="3">{{ old('admin_response',$review->admin_response) }}</textarea></div>
            <div class="admin-actions" style="margin-top:14px"><button class="btn btn-primary">Save</button></div>
        </form>
        <form method="POST" action="{{ route('admin.reviews.destroy',$review) }}" onsubmit="return confirm('Delete this review?')" style="margin-top:10px">
            @csrf @method('DELETE')
            <button class="btn btn-outline" style="color:#b91c1c">Delete</button>
        </form>
    </div>
@empty
    <div class="admin-card">No reviews yet.</div>
@endforelse
</div>
<div style="margin-top:18px">{{ $reviews->links() }}</div>
@endsection
