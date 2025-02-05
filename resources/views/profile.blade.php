@extends('layouts.app')
<!-- Lingkaran Blur dengan Glow -->
<!-- Lingkaran Blur dengan Glow -->

<div
    class="absolute top-40 -left-52 md:top-52 lg:top-80 lg:-left-40 w-[400px] h-[400px] bg-green-400 rounded-full blur-3xl opacity-50 shadow-lg shadow-green-500/50 -z-10 animate-moveCircle1">
</div>
<div
    class="absolute -top-44 -right-56 lg:-top-64 lg:-right-52 w-[420px] h-[420px] bg-pink-400 rounded-full blur-3xl opacity-50 shadow-lg shadow-pink-500/50 -z-10 animate-moveCircle2">
</div>

@section('content')
    <div class="max-w-5xl mx-auto py-12 px-6 relative">
        <!-- Layout Web -->
        <div class=" hidden md:flex items-start bg-gray-900 p-8 rounded-2xl shadow-lg">
            <!-- Profile Section (Left) -->
            <div class="w-1/3 flex flex-col items-center">
                <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-white">
                    <img src="{{ $user->profile_picture ?? 'default-avatar.png' }}" alt="{{ $user->name }}"
                        class="object-cover w-full h-full">
                </div>

            </div>
            <div class="flex flex-col">
                <h2 class="text-2xl font-bold text-white mt-4">{{ $user->name }}</h2>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>

                <div class=" flex flex-col items-center text-white mt-11">
                    <div class="flex gap-5 justify-between w-full text-center">
                        <div>
                            <p class="text-lg font-bold">{{ $watchedCount }}</p>
                            <p class="text-sm text-gray-400">Watched</p>
                        </div>
                        <div>
                            <p class="text-lg font-bold">count</p>
                            <p class="text-sm text-gray-400">Watchlist</p>
                        </div>
                        <div>
                            <p class="text-lg font-bold">count</p>
                            <p class="text-sm text-gray-400">Favorites</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative hidden md:block mt-8 z-[15]">
            <h3 class="text-lg font-bold text-white mb-4">Recently Watched</h3>
            <div class="flex gap-4 overflow-x-auto scrollbar-hidden">
                @foreach ($watched->reverse() as $item)
                    <div class="w-28 flex-shrink-0 group">
                        <a href="{{ route('detail', ['type' => $item->type, 'id' => $item->tmdb_id]) }}"
                            class="block relative transition-all duration-300 ease-in-out group-hover:brightness-125 group-hover:shadow-lg group-hover:-translate-y-2">

                            <img src="https://image.tmdb.org/t/p/w500{{ $item->poster_path }}"
                                class="rounded-lg shadow-md transition-all duration-300 ease-in-out">

                            <p
                                class="text-xs text-gray-400 mt-2 text-center transition-all duration-300 ease-in-out group-hover:text-white">
                                {{ $item->title }}
                            </p>

                            <!-- Efek glow -->
                            <div
                                class="absolute inset-0 bg-cyan-400 opacity-0 blur-xl group-hover:opacity-20 transition-all duration-300 ease-in-out">
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>



        <!-- Layout Mobile -->
        <div class="md:hidden ">
            <div class="bg-gradient-to-b from-cyan-600 to-teal-700 p-8 rounded-3xl shadow-lg">
                <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-white">
                    <img src="{{ $user->profile_picture ?? 'default-avatar.png' }}" alt="{{ $user->name }}"
                        class="object-cover w-full h-full">
                </div>
                <h2 class="text-2xl font-bold text-white mt-4">{{ $user->name }}</h2>
                <p class="text-sm text-gray-200">{{ $user->email }}</p>
            </div>

            <!-- Stats Section -->
            <div class="bg-gray-900 p-6 rounded-2xl shadow-md -mt-6 text-white flex gap-2 justify-around text-center">
                <div>
                    <p class="text-lg font-bold">{{ $watchedCount }}</p>
                    <p class="text-sm text-gray-400">Movies</p>
                </div>
                <div>
                    <p class="text-lg font-bold">count</p>
                    <p class="text-sm text-gray-400">Watchlist</p>
                </div>
                <div>
                    <p class="text-lg font-bold">count</p>
                    <p class="text-sm text-gray-400">Favorites</p>
                </div>
            </div>

            <!-- Recently Watched -->
            <div class="mt-8 px-4">
                <h3 class="text-lg font-bold text-gray-300 mb-4">Recently Watched</h3>
                <div class="flex overflow-x-auto space-x-4 pb-4 scrollbar-hidden">
                    @foreach ($watched as $item)
                        <div class="w-28 flex-shrink-0">
                            <img src="https://image.tmdb.org/t/p/w500{{ $item->poster_path }}"
                                class="rounded-lg shadow-md">
                            <p class="text-xs text-gray-400 mt-2 text-center">{{ $item->title }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Add this CSS for the animation -->
<style>
    /* Animasi lingkaran pertama */
    @keyframes moveCircle1 {
        0% {
            transform: translate(0, 0);
        }

        25% {
            transform: translate(250px, -200px);
        }

        /* Adjusted to move closer to center */
        50% {
            transform: translate(-150px, 100px);
        }

        /* Adjusted */
        75% {
            transform: translate(200px, 50px);
        }

        /* Adjusted */
        100% {
            transform: translate(0, 0);
        }
    }

    /* Animasi lingkaran kedua */
    @keyframes moveCircle2 {
        0% {
            transform: translate(0, 0);
        }

        25% {
            transform: translate(-250px, 150px);
        }

        /* Adjusted to move closer to center */
        50% {
            transform: translate(300px, -180px);
        }

        /* Adjusted */
        75% {
            transform: translate(-200px, -50px);
        }

        /* Adjusted */
        100% {
            transform: translate(0, 0);
        }
    }

    .animate-moveCircle1 {
        animation: moveCircle1 8s ease-in-out infinite;
    }

    .animate-moveCircle2 {
        animation: moveCircle2 8s ease-in-out infinite;
    }
</style>
