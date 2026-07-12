@extends('layouts.admin')
@section('title','New Branch')
@section('page_title','New Branch')
@section('page_subtitle','Add a verified country or city branch contact point.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.branches.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.branches.store') }}">@include('admin.branches._form')</form>
@endsection
