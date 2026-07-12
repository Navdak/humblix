@extends('layouts.admin')
@section('title','Edit Equipment')
@section('page_title','Edit Equipment Item')
@section('page_subtitle','Update catalogue details, image and publishing status.')
@section('page_actions')<a class="btn btn-outline" href="{{ route('admin.equipment.index') }}">Back to Equipment</a>@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.equipment.update',$item) }}" enctype="multipart/form-data">@include('admin.equipment._form')</form>
@endsection
