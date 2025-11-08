<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Error - Diltify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-spotify-black text-white min-h-screen flex items-center justify-center">
    <div class="text-center px-4">
        <div class="mb-8">
            <h1 class="text-9xl font-bold text-spotify-green mb-4">{{ $statusCode ?? 500 }}</h1>
            <h2 class="text-3xl font-bold text-white mb-4">Oops! Something went wrong</h2>
            <p class="text-spotify-text text-lg max-w-md mx-auto mb-8">
                {{ $message ?? 'An error occurred. Please try again later.' }}
            </p>
        </div>

        <div class="flex justify-center space-x-4">
            <a href="{{ url()->previous() }}" class="bg-spotify-gray hover:bg-spotify-dark text-white font-medium px-6 py-3 rounded-full transition-colors border border-gray-700">
                Go Back
            </a>
            @if(session()->has('user_id'))
                <a href="{{ route('dashboard') }}" class="bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-3 rounded-full transition-colors">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-spotify-green hover:bg-green-600 text-black font-medium px-6 py-3 rounded-full transition-colors">
                    Go to Login
                </a>
            @endif
        </div>
    </div>
</body>
</html>

