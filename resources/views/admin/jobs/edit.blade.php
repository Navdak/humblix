@extends('layouts.admin')
@section('title','Edit Job')
@section('page_title','Edit Job Opening')
@section('page_subtitle','Update role details, application path and publishing status.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.jobs.index') }}">Back to Careers</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.jobs.update',$job) }}">@include('admin.jobs._form')</form>
@endsection
