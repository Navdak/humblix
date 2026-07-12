@extends('layouts.admin')
@section('title','Edit Team Member')
@section('page_title','Edit Team Member')
@section('page_subtitle','Update profile details, visibility, and display order.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.team.index') }}">Back to Team</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.team.update',$member) }}" enctype="multipart/form-data">@include('admin.team._form')</form>
@endsection
