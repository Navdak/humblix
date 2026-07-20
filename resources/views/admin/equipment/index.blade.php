@extends('layouts.admin')
@section('title','Equipment')
@section('page_title','Equipment')
@section('page_subtitle','Manage vendor catalogue items for request-based quotations.')
@section('page_actions')<a class="btn btn-primary" href="{{ route('admin.equipment.create') }}"><x-admin-icon name="plus"/> New Item</a>@endsection
@section('content')
<div class="admin-card">
    <div class="admin-list-intro"><strong>Equipment catalogue</strong><span>Published items appear on the vendor/equipment page.</span></div>
    <table class="admin-table"><thead><tr><th>Item</th><th>Category</th><th>Availability</th><th>Published</th><th></th></tr></thead><tbody>
    @forelse($items as $item)
        <tr><td data-label="Item"><strong>{{ $item->name }}</strong><small>{{ $item->short_description ?: 'No summary yet' }}</small></td><td data-label="Category">{{ $item->category }}</td><td data-label="Availability"><span class="badge">{{ str_replace('_',' ', $item->availability_status) }}</span></td><td data-label="Published">{{ $item->is_published ? 'Yes' : 'No' }}</td><td data-label="Actions" class="admin-actions"><a class="btn btn-white" href="{{ route('admin.equipment.edit',$item) }}">Edit</a>@if(auth()->user()?->canDeleteRecords())<form method="POST" action="{{ route('admin.equipment.destroy',$item) }}" onsubmit="return confirm('Delete this equipment item?')">@csrf @method('DELETE')<button class="btn btn-outline" style="color:#b91c1c">Delete</button></form>@endif</td></tr>
    @empty
        <tr><td colspan="5">@include('admin.partials.empty',['title'=>'No equipment yet','message'=>'Add verified vendor catalogue items when product details are approved.','actionUrl'=>route('admin.equipment.create'),'actionLabel'=>'New Item','icon'=>'equipment'])</td></tr>
    @endforelse
    </tbody></table>
    <div style="margin-top:18px">{{ $items->links() }}</div>
</div>
@endsection
