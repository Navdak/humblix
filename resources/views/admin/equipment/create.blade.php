@extends('layouts.admin')
@section('title','New Equipment')
@section('page_title','New Equipment Item')
@section('page_subtitle','Add a request-based vendor catalogue item.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.equipment.index') }}">Cancel</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.equipment.store') }}" enctype="multipart/form-data">@include('admin.equipment._form')</form>
@endsection
