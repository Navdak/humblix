@extends('layouts.admin')
@section('title','Edit Article')
@section('page_title','Edit Article')
@section('page_subtitle','Update content, publishing status, media, and related links.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.articles.index') }}">Back to Articles</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.articles.update',$article) }}" enctype="multipart/form-data">@include('admin.articles._form')</form>
@endsection
