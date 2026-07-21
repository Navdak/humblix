@extends('layouts.admin')
@section('title','New Engineer')
@section('page_title','New Engineer')
@section('page_subtitle','Add an internal field personnel record for enquiry assignment and follow-up.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.engineers.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.engineers.store') }}" enctype="multipart/form-data">
    @include('admin.engineers._form')
</form>
@endsection
