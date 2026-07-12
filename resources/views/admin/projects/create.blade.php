@extends('layouts.admin')
@section('title','New Project')
@section('page_title','New Project')
@section('page_subtitle','Add a completed installation or engineering case study.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.projects.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">@include('admin.projects._form')</form>
@endsection
