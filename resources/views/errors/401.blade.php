@extends('errors.layout')

@section('code', '401')
@section('title', 'Unauthorized')
@section('message', 'You need to sign in to access this page.')
@section('actions')
    <a href="{{ route('login') }}" class="error-btn error-btn-primary">Sign in</a>
    <a href="{{ route('home') }}" class="error-btn error-btn-ghost">Go back home</a>
@endsection