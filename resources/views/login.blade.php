@extends('layouts.app')

@section('content')
    <section class="bg-gradient-to-r from-blue-600 to-blue-500 text-white py-16">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="max-w-lg mx-auto bg-black bg-opacity-60 rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-6">Login to MyWatchLog</h2>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-lg font-semibold">Email</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter your email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-lg font-semibold">Password</label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter your password" required>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember" class="text-blue-500 rounded">
                            <label for="remember" class="text-sm ml-2">Remember me</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-300 hover:text-blue-500">Forgot password?</a>
                        @endif
                    </div>

                    <div class="mb-6">
                        <button type="submit" class="w-full py-2 px-4 bg-blue-600 rounded-lg font-semibold shadow-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-all">
                            Log in
                        </button>
                    </div>

                    <div class="flex items-center justify-center mb-6">
                        <span class="text-gray-400">Or login with</span>
                    </div>

                    <div class="flex justify-center">
                        <!-- Google login button -->
                        <a href="" class="bg-red-600 px-6 py-2 rounded-full text-white font-semibold shadow-lg hover:bg-red-700 transition-all">
                            <svg class="w-5 h-5 mr-2 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="currentColor"><path d="M23.49 12.3c-.25 0-.46.05-.66.13l-.38-.84-.42-.95-.97.42-.44.11-1.33-.94c-1.13-.77-1.91-1.91-2.01-3.32-.1-.89-.17-1.81-.24-2.73-.2-2.35-1.47-4.58-3.52-5.78C13.62 1.41 10.81.25 8.06.25c-5.03 0-8.74 4.61-7.92 9.64 0 .21.02.42.05.63-.19 2.08.33 4.18 1.54 5.9l1.06-.64c-.12-.12-.19-.28-.21-.44l-.01-.01c-1.46-.06-2.77-.53-3.61-1.4-1.62-1.62-2.46-3.96-2.33-6.34-.16-1.25.41-2.44 1.4-3.17 1.25-1.19 3.01-1.49 4.46-.88l1.12-.11c1.36.19 2.6.88 3.55 1.91l1.48 1.21 3.27-2.47-.02 5.19h-2.46l-.41-1.33-1.21-.97-.04-.41c-.63-.07-1.23-.18-1.86-.31-.74-.15-1.48-.29-2.22-.43zM27.93 20.7c-.04.06-.12.1-.19.1-.08 0-.16-.05-.19-.13-1.07-3.16-1.98-6.58-3.23-9.95 1.64-.7 3.15-.62 4.59.03-.04.65-.18 1.34-.42 2.01-.27.71-.49 1.44-.75 2.17z"></path></svg> Google
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </section>
@endsection
