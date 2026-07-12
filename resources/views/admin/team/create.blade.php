@extends('layouts.admin')
@section('title','New Team Member')
@section('page_title','New Team Member')
@section('page_subtitle','Add an engineer, delegate, or support team profile.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.team.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data">@include('admin.team._form')</form>
@endsection
