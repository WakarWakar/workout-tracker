@extends('layouts.app')

@section('title', 'Page Not Found | Workout Tracker')

@section('content')
<div class="mx-auto max-w-md py-12 text-center">
    <p class="text-5xl font-bold text-indigo-600">404</p>
    <h1 class="mt-4 text-xl font-semibold text-gray-900">Page not found</h1>
    <p class="mt-2 text-sm text-gray-500">Sorry, the page you're looking for doesn't exist, may have been moved, or requires logging in.</p>
    <a href="/" class="btn btn-primary mt-6">Go back home</a>
</div>
@endsection
