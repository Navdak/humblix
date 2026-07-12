@extends('layouts.admin')
@section('title','New Job')
@section('page_title','New Job Opening')
@section('page_subtitle','Create a verified role for the public careers page.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.jobs.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.jobs.store') }}">@include('admin.jobs._form')</form>
@endsection
