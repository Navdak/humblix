@extends('layouts.admin')
@section('title','Edit Engineer')
@section('page_title','Edit Engineer')
@section('page_subtitle','Update internal assignment, contact and availability details.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.engineers.index') }}">Back to Engineers</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.engineers.update', $engineer) }}" enctype="multipart/form-data">
    @include('admin.engineers._form')
</form>
@endsection
