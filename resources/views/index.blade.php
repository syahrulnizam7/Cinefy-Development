@extends('layouts.app')

<!-- Lingkaran Blur dengan Glow gerak -->
<div
    class="fixed top-40 -left-52 md:top-52 lg:top-80 lg:-left-40 w-72 h-72 md:w-[400px] md:h-[400px] bg-green-400 rounded-full blur-3xl opacity-50 shadow-lg shadow-green-500/50 -z-10 animate-moveCircle1">
</div>
<div
    class="fixed -top-32 -right-40 md:-top-44 md:-right-56 w-72 h-72 md:w-[420px] md:h-[420px] bg-pink-400 rounded-full blur-3xl opacity-50 shadow-lg shadow-pink-500/50 -z-10 animate-moveCircle2">
</div>


@section('content')

    <!-- Tambahkan Typed.js di Head atau sebelum penutup </body> -->
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>

    <!-- Hero Section -->
    <section class="relative text-white lg:pt-20 ">
        <div class="relative flex flex-col lg:grid lg:grid-cols-2 gap-8 md:gap-12 items-center w-full md:px-6 ">
            <!-- Bagian Kiri (Text) - Muncul hanya di Desktop -->
            <div class="hidden lg:block z-20">
                <h1 class="text-3xl md:text-5xl font-bold leading-tight">
                    Track Your Favorite <br><span class="text-blue-500" id="typing-text"></span></br>
                </h1>
                <p class="text-lg mt-4 md:mt-6 opacity-80">
                    Manage your watched content, discover new shows, and add to your collection seamlessly.
                </p>
                <div class="mt-6 flex gap-3">
                    <a href="#"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-full font-semibold shadow-lg hover:bg-blue-700 transition">Start
                        Watching</a>
                    <a href="{{ route('explore.index') }}"
                        class="border border-blue-600 text-blue-600 px-5 py-2.5 rounded-full font-semibold hover:bg-blue-600 hover:text-white transition">Explore
                        Now</a>
                </div>
            </div>

            <!-- Bagian Kanan (Gambar) - Muncul dulu di Mobile -->
            <div class="w-full h-full relative flex justify-center">
                <img src="{{ asset('images/hero.png') }}" alt="Hero Image"
                    class="w-full max-w-md sm:max-w-lg md:max-w-xl h-auto object-contain opacity-70 z-0">

                <!-- Overlay yang menutupi seluruh layar -->
                <div
                    class="absolute h-full top-0 left-0 w-full  flex flex-col justify-center items-center text-center p-4 bg-gradient-to-b from-black/80 via-black/70 to-transparent lg:hidden">
                    <h1 class="text-2xl sm:text-3xl font-bold mt-20">
                        Track Your Favorite <span class="text-blue-500" id="typing-text-mobile"></span>
                    </h1>
                    <p class="text-sm sm:text-base mt-2 sm:mt-4 opacity-80">Manage your watched content, discover new shows,
                        and add to your collection seamlessly.</p>
                    <div class="mt-4 sm:mt-6 flex gap-3 justify-center">
                        <a href="#"
                            class="bg-blue-600 text-white px-5 py-2.5 rounded-full font-semibold shadow-lg hover:bg-blue-700 transition">Start
                            Watching</a>
                        <a href="{{ route('explore.index') }}"
                            class="border border-blue-600 text-blue-600 px-5 py-2.5 rounded-full font-semibold hover:bg-blue-600 hover:text-white transition">Explore
                            Now</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Inisialisasi Typed.js -->
    <script>
        var options = {
            strings: ["Movies", "Series", "Anime"],
            typeSpeed: 100, // Kecepatan mengetik
            backSpeed: 50, // Kecepatan menghapus
            backDelay: 1500, // Waktu jeda sebelum menghapus
            loop: true, // Agar animasi terus berulang
            showCursor: true, // Menampilkan kursor
            cursorChar: "|", // Simbol kursor
        };

        new Typed("#typing-text", options);
        new Typed("#typing-text-mobile", options);
    </script>



    <!-- Trending Section -->
    <section class="w-full px-4 py-6 md:px-6 text-white relative z-20">
        <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Trending</h2>
        <div class="relative overflow-x-scroll scrollbar-hidden pb-6">
            <div class="flex space-x-6 overflow-visible">
                @foreach ($trending as $item)
                    <a href="{{ route('detail', ['type' => $item['media_type'], 'id' => $item['id']]) }}"
                        class="relative bg-gray-900 rounded-xl shadow-2xl w-36 lg:w-48 flex-shrink-0 overflow-hidden group">
                        <div class="relative w-full h-52 lg:h-72 overflow-hidden rounded-xl">
                            <img src="https://image.tmdb.org/t/p/w500{{ $item['poster_path'] }}"
                                alt="{{ $item['title'] ?? $item['name'] }}"
                                class="w-full h-full object-cover rounded-xl transform transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <h3 class="text-lg font-extrabold">{{ $item['title'] ?? $item['name'] }}</h3>
                            <p class="text-sm opacity-80">
                                {{ isset($item['release_date']) ? \Carbon\Carbon::parse($item['release_date'])->translatedFormat('d M Y') : '-' }}
                            </p>
                            <div class="mt-2 flex items-center space-x-2">
                                <span
                                    class="text-sm font-semibold bg-yellow-500 px-3 py-1 rounded-full text-black shadow-md">
                                    ⭐ {{ number_format($item['vote_average'], 1) }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Latest Trailers Section --}}
    <section
        class="w-full mt-4 relative  mx-auto px-6  py-10 text-white group transition-all duration-700 sm:w-full w- md:w-full lg:w-full"
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