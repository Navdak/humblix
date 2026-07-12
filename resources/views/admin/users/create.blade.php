@extends('layouts.admin')
@section('title','New User')
@section('page_title','New Admin User')
@section('page_subtitle','Create a secure administrative account and assign its role.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.users.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.users.store') }}">@include('admin.users._form')</form>
@endsection
