@extends('layouts.admin')
@section('title','New Article')
@section('page_title','New Article')
@section('page_subtitle','Create a new guide, update, or educational resource.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.articles.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">@include('admin.articles._form')</form>
@endsection
