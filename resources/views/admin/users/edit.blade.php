@extends('layouts.admin')
@section('title','Edit User')
@section('page_title','Edit Admin User')
@section('page_subtitle','Update account details, permissions, and access status.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.users.index') }}">Back to Users</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.users.update',$user) }}" enctype="multipart/form-data">@include('admin.users._form')</form>
@endsection
