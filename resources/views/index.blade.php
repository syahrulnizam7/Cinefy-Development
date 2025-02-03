@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative text-white mt-28 lg:mt-24 lg:-mb-12 sm:mt-32">
        <div class="container mx-auto px-6 lg:px-15">
            <div class="relative flex flex-col lg:grid lg:grid-cols-2 gap-12 items-center">

                <!-- Bagian Kiri (Text) - Muncul hanya di Desktop -->
                <div class="hidden lg:block">
                    <h1 class="text-4xl md:text-6xl font-bold leading-tight">Track Your Favorite <span
                            class="text-blue-500">Movies, Series & Anime</span></h1>
                    <p class="text-lg mt-6 opacity-80">Manage your watched content, discover new shows, and add to your
                        collection seamlessly.</p>
                    <div class="mt-8 flex gap-4">
                        <a href="#"
                            class="bg-blue-600 text-white px-6 py-3 rounded-full font-semibold shadow-lg hover:bg-blue-700 transition">Start
                            Watching</a>
                        <a href="#"
                            class="border border-blue-600 text-blue-600 px-6 py-3 rounded-full font-semibold hover:bg-blue-600 hover:text-white transition">Explore
                            Now</a>
                    </div>
                </div>

                <!-- Bagian Kanan (Gambar) - Muncul dulu di Mobile -->
                <div class="  w-full relative flex justify-center">
                    <img src="{{ asset('images/hero.png') }}" alt="Hero Image"
                        class="w-full h-auto sm:h-[90vh] lg:h-[80vh] object-contain shadow-lg">

                    <div
                        class="absolute inset-0 flex flex-col justify-center text-center p-6 bg-gradient-to-r from-black/70 via-gray-900/70 to-black/70 lg:hidden">
                        <h1 class="text-4xl font-bold">Track Your Favorite <span class="text-blue-500">Movies, Series
                                &
                                Anime</span></h1>
                        <p class="text-lg mt-4 opacity-80">Manage your watched content, discover new shows, and add to
                            your collection seamlessly.</p>
                        <div class="mt-6 flex gap-4 justify-center">
                            <a href="#"
                                class="bg-blue-600 text-white px-6 py-3 rounded-full font-semibold shadow-lg hover:bg-blue-700 transition">Start
                                Watching</a>
                            <a href="#"
                                class="border border-blue-600 text-blue-600 px-6 py-3 rounded-full font-semibold hover:bg-blue-600 hover:text-white transition">Explore
                                Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Trending Section --}}
    <section class="container mx-auto px-6 py-1 text-white">
        <h2 class="text-2xl lg:text-3xl font-bold mb-6">Trending</h2>
        <div class="overflow-x-auto scrollbar-hidden pt-4 pb-4 h-[335px]">
            <div class="flex space-x-4 h-auto">
                @foreach ($trending as $item)
                    <a href=""
                        class="bg-gray-800 rounded-lg shadow-lg w-40 flex-shrink-0 transform transition-all duration-300 hover:cursor-pointer hover:scale-105 hover:shadow-2xl hover:bg-gray-700 hover:overflow-visible">
                        <img src="https://image.tmdb.org/t/p/w500{{ $item['poster_path'] }}"
                            alt="{{ $item['title'] ?? $item['name'] }}"
                            class="w-full h-52 object-cover object-top transition-all duration-300 transform group-hover:scale-110">
                        <div class="p-2">
                            <h3 class="text-sm font-semibold">{{ $item['title'] ?? $item['name'] }}</h3>
                            <p class="text-xs opacity-75">
                                {{ isset($item['release_date']) ? \Carbon\Carbon::parse($item['release_date'])->translatedFormat('d M Y') : (isset($item['first_air_date']) ? \Carbon\Carbon::parse($item['first_air_date'])->translatedFormat('d M Y') : '-') }}
                            </p>
                            <p class="mt-1 -mb-1 text-xs bg-blue-500 px-2 py-1 inline-block rounded">
                                {{ $item['vote_average'] * 10 }}%
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>




    {{-- Latest Trailers Section --}}
    <section
        class="w-full mt-4 relative container mx-auto px-6  py-10 text-white group transition-all duration-700 sm:w-full w- md:w-full lg:w-full"
        x-data="{ playing: false, trailerUrl: '' }"
        :style="'background-image: url(' + trailerUrl +
            '); background-size: cover; background-position: center; background-blend-mode: overlay;'">

        <!-- Overlay warna biru semi-transparan dengan vignette & transisi -->
        <div
            class="absolute inset-0 bg-blue-500 bg-opacity-80 transition-all duration-700 group-hover:bg-opacity-60 
bg-[radial-gradient(circle,rgba(0,0,0,0.4)_10%,rgba(0,0,0,0.8)_90%)] group-hover:opacity-50 w-full sm:w-full w- md:w-full lg:w-full">
        </div>

        <!-- Konten -->
        <div class="relative z-10 transition-all duration-700">
            <h2 class="text-2xl lg:text-3xl font-bold mb-6">Latest Trailers</h2>
            <div class="flex space-x-4 overflow-x-auto scrollbar-hidden">
                @foreach ($latestTrailers as $movie)
                    @if ($movie['trailer_key'])
                        <div class="w-60 flex-shrink-0 transition-all duration-700"
                            @mouseover="trailerUrl = 'https://img.youtube.com/vi/{{ $movie['trailer_key'] }}/maxresdefault.jpg'"
                            @click="playing = true; trailerUrl = 'https://www.youtube.com/embed/{{ $movie['trailer_key'] }}?autoplay=1&rel=0&showinfo=0'">

                            <!-- Wrapper untuk Thumbnail & Video -->
                            <div class="relative pb-[56.25%] overflow-hidden rounded-lg shadow-lg group">

                                <!-- Thumbnail dengan ikon play -->
                                <div class="absolute top-0 left-0 w-full h-full bg-black cursor-pointer">
                                    <img class="w-full h-full object-cover"
                                        src="https://img.youtube.com/vi/{{ $movie['trailer_key'] }}/maxresdefault.jpg"
                                        alt="{{ $movie['title'] }}">

                                    <!-- Ikon Play di tengah -->
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div
                                            class="w-14 h-14 bg-white bg-opacity-75 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-8 h-8 text-black" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.5 4.5l9 5-9 5V4.5z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-sm font-semibold mt-2">{{ $movie['title'] }}</h3>
                            <p class="text-xs opacity-75">
                                {{ \Carbon\Carbon::parse($movie['release_date'])->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Modal untuk menampilkan video -->
        <div x-show="playing" x-transition:enter="transition opacity-0 ease-in-out duration-500"
            x-transition:enter-end="opacity-100" x-transition:leave="transition opacity-100 ease-in-out duration-500"
            x-transition:leave-start="opacity-0" @click.away="playing = false"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-75 backdrop-blur-md">

            <!-- Konten Modal dengan animasi pop-up -->
            <div x-show="playing" x-transition:enter="transition transform ease-out duration-500 scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition transform ease-in duration-500 scale-105 opacity-0"
                x-transition:leave-start="scale-100 opacity-100" class="relative bg-white rounded-lg w-full max-w-3xl p-4">

                <!-- Close Button -->
                <button @click="playing = false" class="absolute top-4 right-6 text-red-500 text-xl font-bold">X</button>

                <!-- Iframe video -->
                <iframe class="w-full h-80 rounded-lg shadow-lg" :src="trailerUrl" frameborder="0"
                    allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        </div>


    </section>
