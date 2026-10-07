@extends('errors.layout')

@section('code', '429')
@section('title', 'Too Many Requests')
@section('message', 'You have sent too many requests in a short time. Please wait a moment and try again.')
@section('actions')
    <button type="button" class="error-btn error-btn-primary" onclick="window.location.reload()">Try again</button>
    <a href="{{ route('home') }}" class="error-btn error-btn-ghost">Go back home</a>
@endsection