@extends('layouts.admin')
@section('title','Edit Branch')
@section('page_title','Edit Branch')
@section('page_subtitle','Update branch contact, coverage and publishing status.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.branches.index') }}">Back to Branches</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.branches.update',$branch) }}">@include('admin.branches._form')</form>
@endsection
