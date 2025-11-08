<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Diltify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-spotify-black via-spotify-gray to-spotify-black min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="bg-spotify-dark rounded-lg shadow-2xl p-8 border border-spotify-gray">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Diltify</h1>
                <p class="text-spotify-text">Create your account</p>
            </div>

            @include('components.alert')

            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-white mb-2">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 bg-spotify-gray border border-gray-700 rounded-lg text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent"
                        placeholder="Enter your name">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-spotify-gray border border-gray-700 rounded-lg text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent"
                        placeholder="Enter your email">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 bg-spotify-gray border border-gray-700 rounded-lg text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent"
                        placeholder="Enter your password">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 bg-spotify-gray border border-gray-700 rounded-lg text-white placeholder-spotify-text focus:outline-none focus:ring-2 focus:ring-spotify-green focus:border-transparent"
                        placeholder="Confirm your password">
                </div>

                <button type="submit"
                    class="w-full bg-spotify-green hover:bg-green-600 text-black font-bold py-3 px-4 rounded-full transition-colors">
                    Sign Up
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-spotify-text">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-spotify-green hover:text-green-400 font-medium">
                        Log in
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

