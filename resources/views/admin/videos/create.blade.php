@extends('layouts.admin')
@section('title','New Video')
@section('page_title','New Video')
@section('page_subtitle','Add an external or uploaded video to the Humelix library.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.videos.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.videos.store') }}" enctype="multipart/form-data">@include('admin.videos._form')</form>
@endsection
