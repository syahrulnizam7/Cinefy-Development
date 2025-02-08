@extends('layouts.app')
<!-- Lingkaran Blur dengan Glow gerak -->
<div
    class="fixed top-40 -left-52 md:top-52 lg:top-80 lg:-left-40 w-[400px] h-[400px] bg-green-400 rounded-full blur-3xl opacity-50 shadow-lg shadow-green-500/50 -z-10 animate-moveCircle1">
</div>
<div
    class="fixed -top-44 -right-56 lg:-top-64 lg:-right-52 w-[420px] h-[420px] bg-pink-400 rounded-full blur-3xl opacity-50 shadow-lg shadow-pink-500/50 -z-10 animate-moveCircle2">
</div>
@section('content')
<div class="max-w-6xl lg:mt-10 mx-auto py-12 px-6 relative">
    <!-- Desktop Layout -->
    <div class="hidden md:flex flex-col bg-gray-900 p-8 rounded-2xl shadow-lg relative overflow-hidden">
        <!-- Animated Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 opacity-30 blur-3xl"></div>

        <div class="flex items-center gap-8 relative z-10">
            <!-- Profile Picture -->
            <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-white">
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="object-cover w-full h-full">
            </div>
            
            <!-- User Info -->
            <div>
                <h2 class="text-3xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-sm text-gray-300"><span>@</span>{{ $user->username }}</p>
                <div class="flex mt-4 space-x-8 text-white">
                    <div class="text-center">
                        <p class="text-lg font-bold">{{ $watchedCount }}</p>
                        <p class="text-sm text-gray-400">Watched</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold">{{ $watchlistCount }}</p>
                        <p class="text-sm text-gray-400">Watchlist</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold">{{ $favoriteCount }}</p>
                        <p class="text-sm text-gray-400">Favorites</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Layout -->
    <div class="md:hidden bg-gradient-to-r from-blue-700 to-purple-800 p-8 rounded-3xl shadow-lg text-center relative">
        <div class="absolute inset-0 bg-black opacity-30 rounded-3xl"></div>
        <div class="relative z-10">
            <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-white">
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="object-cover w-full h-full">
            </div>
            <h2 class="text-2xl font-bold text-white mt-4">{{ $user->name }}</h2>
            <p class="text-sm text-gray-200"><span>@</span>{{ $user->username }}</p>
            <div class="mt-4 flex justify-around text-white">
                <div class="text-center">
                    <p class="text-lg font-bold">{{ $watchedCount }}</p>
                    <p class="text-sm text-gray-300">Watched</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-bold">{{ $watchlistCount }}</p>
                    <p class="text-sm text-gray-300">Watchlist</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-bold">{{ $favoriteCount }}</p>
                    <p class="text-sm text-gray-300">Favorites</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recently Watched Section -->
    <div class="mt-8">
        <h3 class="text-xl font-bold text-white mb-4">Recently Watched</h3>
        <div class="flex gap-4 overflow-x-auto scrollbar-hidden">
            @foreach ($watched as $item)
                <div class="w-32 flex-shrink-0 group">
                    <a href="{{ route('detail', ['type' => $item->type, 'id' => $item->tmdb_id]) }}" class="block relative">
                        <img src="https://image.tmdb.org/t/p/w500{{ $item->poster_path }}" class="rounded-lg shadow-md transition-transform transform group-hover:scale-105">
                        <p class="text-xs text-gray-400 mt-2 text-center">{{ $item->title }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Watchlist Section -->
    <div class="mt-8">
        <h3 class="text-xl font-bold text-white mb-4">Watchlist</h3>
        <div class="flex gap-4 overflow-x-auto scrollbar-hidden">
            @foreach ($watchlist as $item)
                <div class="w-32 flex-shrink-0 group">
                    <a href="{{ route('detail', ['type' => $item->type, 'id' => $item->tmdb_id]) }}" class="block relative">
                        <img src="https://image.tmdb.org/t/p/w500{{ $item->poster_path }}" class="rounded-lg shadow-md transition-transform transform group-hover:scale-105">
                        <p class="text-xs text-gray-400 mt-2 text-center">{{ $item->title }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Favorite -->
    <div class="mt-8">
        <h3 class="text-xl font-bold text-white mb-4">Favorite</h3>
        <div class="flex gap-4 overflow-x-auto scrollbar-hidden">
            @foreach ($favorite as $item)
                <div class="w-32 flex-shrink-0 group">
                    <a href="{{ route('detail', ['type' => $item->type, 'id' => $item->tmdb_id]) }}" class="block relative">
                        <img src="https://image.tmdb.org/t/p/w500{{ $item->poster_path }}" class="rounded-lg shadow-md transition-transform transform group-hover:scale-105">
                        <p class="text-xs text-gray-400 mt-2 text-center">{{ $item->title }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
