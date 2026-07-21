@extends('layouts.admin')
@section('title','New Safety Topic')
@section('page_title','New Safety Topic')
@section('page_subtitle','Create a Safety Framework topic for the public Safety Centre.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.safety.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.safety.store') }}" enctype="multipart/form-data">
    @include('admin.safety._form')
</form>
@endsection
