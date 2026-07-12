@extends('layouts.admin')
@section('title','Edit Video')
@section('page_title','Edit Video')
@section('page_subtitle','Update video metadata, media, publishing and placement.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.videos.index') }}">Back to Videos</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.videos.update',$video) }}" enctype="multipart/form-data">@include('admin.videos._form')</form>
@endsection
