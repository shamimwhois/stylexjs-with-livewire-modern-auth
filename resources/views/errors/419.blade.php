@extends('errors.layout')

@section('code', '419')
@section('title', 'Page Expired')
@section('message', 'Your session has expired. Refresh the page and try again.')
@section('actions')
    <button type="button" class="error-btn error-btn-primary" onclick="window.location.reload()">Refresh page</button>
    <a href="{{ route('home') }}" class="error-btn error-btn-ghost">Go back home</a>
@endsection