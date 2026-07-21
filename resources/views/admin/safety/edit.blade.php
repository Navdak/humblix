@extends('layouts.admin')
@section('title','Edit Safety Topic')
@section('page_title','Edit Safety Topic')
@section('page_subtitle','Update Safety Framework content, image, status, and optional video.')
@section('page_actions')
    <div class="admin-actions">
        <a class="btn btn-outline" href="{{ route('admin.safety.index') }}">Back to Safety</a>
        <a class="btn btn-white" href="{{ route('safety.topic',$topic) }}" target="_blank" rel="noopener"><x-admin-icon name="external"/> View Public Topic</a>
    </div>
@endsection
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.safety.update',$topic) }}" enctype="multipart/form-data">
    @include('admin.safety._form')
</form>
@endsection
