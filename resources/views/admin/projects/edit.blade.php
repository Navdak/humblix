@extends('layouts.admin')
@section('title','Edit Project')
@section('page_title','Edit Project')
@section('page_subtitle','Update project details, outcomes, status, and imagery.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.projects.index') }}">Back to Projects</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.projects.update',$project) }}" enctype="multipart/form-data">@include('admin.projects._form')</form>
@endsection
