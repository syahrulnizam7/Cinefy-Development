<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @vite('resources/css/app.css')
</head>

<body class="overflow-y-auto w-full overflow-hidden bg-gradient-to-r from-black via-gray-900 to-black ">

    {{-- Navigasi TOP Menu --}}
    <nav id="navbar"
        class="fixed z-20 top-0 left-0 w-full py-6 px-6 bg-gradient-to-r from-gray-900 to-black text-white transition-all duration-300"
        x-data="{ navOpen: true }">
        <div class="container mx-auto flex justify-between items-center">
            <img src="{{ asset('images/logo_fordarktheme.png') }}" alt="My Image" class="h-16 order-1 sm:order-2">
            <button @click ="navOpen =! navOpen" id="hamburger" name="hamburger" type="button"
                class="hover:bg-blue-700 transition bg-blue-600 rounded-md w-12 h-12 flex flex-col items-center justify-center gap-1.5 order-2 sm:order-1 lg:hidden">
                <span class="w-6 h-[2px] bg-white"></span>
                <span class="w-6 h-[2px] bg-white"></span>
                <span class="w-6 h-[2px] bg-white"></span>
            </button>
            <div class="order-3 hidden sm:block">
                <button
                    class="grow bg-blue-600 text-white px-8 py-4 font-bold rounded-full text-sm hover:bg-blue-700 transition">Login</button>
                <button
                    class="grow bg-blue-800 text-white px-8 py-4 font-bold rounded-full text-sm hover:bg-blue-700 transition">Sign
                    Up</button>
            </div>
            <div class="hidden lg:block order-2">
                <ul class="flex gap-16">
                    <li class="text-white font-bold text-sm font-Circular hover:text-blue-600"><a
                            href="">Home</a></li>
                    <li class="text-white font-bold opacity-80 text-sm font-Circular hover:text-blue-600"><a
                            href="">Search</a></li>
                    <li class="text-white font-bold opacity-80 text-sm font-Circular hover:text-blue-600"><a
                            href="">Add</a></li>
                    <li class="text-white font-bold opacity-80 text-sm font-Circular hover:text-blue-600"><a
                            href="">Activity</a></li>
                    <li class="text-white font-bold opacity-80 text-sm font-Circular hover:text-blue-600"><a
                            href="">Profile</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Navbar -->
        <div x-show="navOpen"
            class="fixed scale-75 rounded-full z-20 bottom-1 right-1 left-1 p-4 lg:hidden bg-blue-600 hover:opacity-80 opacity-70"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10">
            <ul class="flex justify-between">
                <li class="group">
                    <a href="" class="text-white flex flex-col items-center gap-1 group-hover:text-blue-500">
                        <ion-icon name="home" class="text-2xl group-hover:text-blue-500"></ion-icon>
                        <span class="text-white text-base font-bold group-hover:text-blue-500">Home</span>
                    </a>
                </li>
                <li class="group">
                    <a href="" class="flex flex-col items-center gap-1 group-hover:text-blue-500">
                        <ion-icon name="search"
                            class="text-2xl text-white opacity-100 group-hover:text-blue-500 group-hover:opacity-100"></ion-icon>
                        <span
                            class="text-white opacity-100 text-base font-normal group-hover:text-blue-500 group-hover:opacity-100">Search</span>
                    </a>
                </li>
                <li class="group">
                    <a href="" class="flex flex-col items-center gap-1 group-hover:text-blue-500">
                        <ion-icon name="add-circle"
                            class="text-2xl text-white opacity-100 group-hover:text-blue-500 group-hover:opacity-100"></ion-icon>
                        <span
                            class="text-white opacity-100 text-base font-normal group-hover:text-blue-500 group-hover:opacity-100">Add</span>
                    </a>
                </li>
                <li class="group">
                    <a href="" class="flex flex-col items-center gap-1 group-hover:text-blue-500">
                        <ion-icon name="hourglass"
                            class="text-2xl text-white opacity-100 group-hover:text-blue-500 group-hover:opacity-100"></ion-icon>
                        <span
                            class="text-white opacity-100 text-base font-normal group-hover:text-blue-500 group-hover:opacity-100">Activity</span>
                    </a>
                </li>
                <li class="group">
                    <a href="" class="flex flex-col items-center gap-1 group-hover:text-blue-500">
                        <ion-icon name="person"
                            class="text-2xl text-white opacity-100 group-hover:text-blue-500 group-hover:opacity-100"></ion-icon>
                        <span
                            class="text-white opacity-100 text-base font-normal group-hover:text-blue-500 group-hover:opacity-100">Profile</span>
                    </a>
                </li>
            </ul>
        </div>


    </nav>

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
                        <h1 class="text-4xl font-bold">Track Your Favorite <span class="text-blue-500">Movies, Series &
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

    {{-- Populer Section --}}
    <section class="container mx-auto px-6 py-6 text-white">
        <h2 class="text-2xl lg:text-3xl font-bold mb-6">Trending</h2>
        <div class="flex space-x-4 h-auto overflow-x-auto scrollbar-hidden">
            @foreach ($movies as $movie)
                <div
                    class="bg-gray-800 rounded-lg shadow-lg w-40 flex-shrink-0 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:bg-gray-700 hover:overflow-visible">
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                        class="w-full h-48 object-cover transition-all duration-300 transform group-hover:scale-110">
                    <div class="p-2">
                        <h3 class="text-sm font-semibold">{{ $movie['title'] }}</h3>
                        <p class="text-xs opacity-75">
                            {{ \Carbon\Carbon::parse($movie['release_date'])->translatedFormat('d M Y') }}
                        </p>
                        <p class="mt-1 text-xs bg-blue-500 px-2 py-1 inline-block rounded">
                            {{ $movie['vote_average'] * 10 }}%
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>


    {{-- Latest Trailers Section --}}
    <section
        class="w-full my-6 relative container mx-auto px-6  py-10 text-white group transition-all duration-700 sm:w-full w- md:w-full lg:w-full"
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
                                                <path fill-rule="evenodd" d="M6.5 4.5l9 5-9 5V4.5z"
                                                    clip-rule="evenodd" />
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
                x-transition:leave-start="scale-100 opacity-100"
                class="relative bg-white rounded-lg w-full max-w-3xl p-4">

                <!-- Close Button -->
                <button @click="playing = false"
                    class="absolute top-4 right-6 text-red-500 text-xl font-bold">X</button>

                <!-- Iframe video -->
                <iframe class="w-full h-80 rounded-lg shadow-lg" :src="trailerUrl" frameborder="0"
                    allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
        </div>


    </section>




    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>
