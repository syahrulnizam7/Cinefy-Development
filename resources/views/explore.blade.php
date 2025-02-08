@extends('layouts.app')

@section('content')
    <div class=" mt-20 mx-auto lg:mt-16 px-4 py-8 text-white relative">
        <h1
            class="relative text-2xl font-semibold text-center text-white 
            lg:before:content-[''] lg:before:absolute lg:before:w-[450px] before:h-[2px] before:bg-gray-700 before:left-[-40px] before:top-1/2 
            lg:after:content-[''] lg:after:absolute lg:after:w-[450px] after:h-[2px] after:bg-gray-700 after:right-[-40px] after:top-1/2">
            Explore Your Next Watch
        </h1>

        <form method="GET" action="{{ route('explore.index') }}" class="flex flex-wrap gap-2 w-full md:w-auto mt-4">
            <!-- Genre Filter -->
            <select name="genre" class="p-2 rounded bg-gray-800 border border-gray-700">
                <option value="">All Genres</option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre['id'] }}" {{ request('genre') == $genre['id'] ? 'selected' : '' }}>
                        {{ $genre['name'] }}
                    </option>
                @endforeach
            </select>

            <!-- Year Filter -->
            <input type="number" name="year" min="1900" max="{{ date('Y') }}" value="{{ request('year') }}"
                class="p-2 rounded bg-gray-800 border border-gray-700" placeholder="Year">

            <!-- Rating Filter -->
            <input type="number" name="rating" min="0" max="10" step="0.1"
                value="{{ request('rating') }}" class="p-2 rounded bg-gray-800 border border-gray-700"
                placeholder="Min Rating">

            <!-- Type Filter -->
            <select name="type" class="p-2 rounded bg-gray-800 border border-gray-700">
                <option value="" {{ request('type') == '' ? 'selected' : '' }}>All</option>
                <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movies</option>
                <option value="tv" {{ request('type') == 'tv' ? 'selected' : '' }}>TV Shows</option>
            </select>

            <!-- Search Filter -->
            <input type="text" name="search" value="{{ request('search') }}"
                class="p-2 rounded bg-gray-800 border border-gray-700" placeholder="Search by title" autocomplete="off">

            <!-- Apply Button -->
            <button type="submit" class="bg-cyan-600 px-4 py-2 rounded-lg text-white">Apply</button>
        </form>





        <!-- Autocomplete Results -->
        <div id="autocomplete-results"
            class="absolute bg-gray-800 text-white w-full mt-1 rounded-md shadow-md hidden max-h-60 overflow-y-auto z-10">
        </div>

        <!-- Movies/TV Shows List -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-8">
            @foreach ($items as $index => $item)
                <a href="{{ route('detail', ['type' => $item['media_type'] ?? $mediaType, 'id' => $item['id']]) }}"
                    class="group block bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-all duration-300 hover:shadow-xl relative hover:brightness-110 opacity-0 animate-fadeIn"
                    style="animation-delay: {{ $index * 100 }}ms">
                    <img src="{{ $item['poster_path'] ? 'https://image.tmdb.org/t/p/w200' . $item['poster_path'] : asset('/images/noimg.png') }}"
                        alt="{{ $item['title'] ?? $item['name'] }}"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition-all group-hover:brightness-125">


                    <div class="p-2 absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black to-transparent">
                        <h2 class="text-sm font-bold text-white truncate">{{ $item['title'] ?? $item['name'] }}</h2>
                        <p class="text-xs opacity-70 mt-1 text-white">⭐ {{ $item['vote_average'] ?? 'N/A' }}/10 | 📅
                            {{ $item['release_date'] ?? ($item['first_air_date'] ?? 'Unknown') }}</p>

                    </div>
                </a>
            @endforeach
        </div>

        <!-- Load More Button -->
        <div class="flex justify-center mt-8">
            <button id="load-more-btn" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-all">
                Load More
            </button>
        </div>

        <script>
            let page = {{ $page }};
            let loading = false; // Prevent multiple AJAX requests at the same time

            document.getElementById('load-more-btn').addEventListener('click', function() {
                if (loading) return; // Prevent multiple clicks
                loading = true;

                // Dapatkan semua parameter filter dari query string
                let filters = new URLSearchParams(window.location.search);

                // Ganti nomor halaman dengan halaman berikutnya
                filters.set('page', page + 1);

                // Buat URL baru dengan filter yang diperbarui
                let url =
                    "{{ route('explore.index', ['page' => '__page__', 'search' => request('search'), 'genre' => request('genre'), 'year' => request('year'), 'rating' => request('rating'), 'type' => request('type')]) }}";
                url = url.replace('__page__', page + 1);

                // Menambahkan filter dari URL saat ini
                filters.forEach((value, key) => {
                    url += `&${key}=${value}`;
                });
                // Perform the AJAX request to get the next page of items
                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        let grid = document.querySelector('.grid');
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(data, 'text/html');
                        let newItems = doc.querySelectorAll('.grid > a'); // Ambil elemen <a> baru

                        if (newItems.length === 0) {
                            document.getElementById('load-more-btn').style.display = 'none';
                            return;
                        }

                        // Tambahkan item baru ke grid
                        newItems.forEach((item, index) => {
                            item.classList.add('opacity-0'); // Tambahkan opacity-0 sebelum masuk ke DOM
                            grid.appendChild(item);

                            // Aktifkan animasi fadeIn setelah elemen ditambahkan ke DOM
                            setTimeout(() => {
                                item.classList.add('animate-fadeIn');
                                item.style.animationDelay =
                                    `${index * 100}ms`; // Delay animasi untuk efek berurutan
                                item.classList.remove(
                                    'opacity-0'); // Hapus opacity-0 agar muncul dengan animasi
                            }, 50);
                        });

                        page++; // Update halaman
                        loading = false; // Reset loading flag
                    })
                    .catch(error => {
                        console.error('Error loading more items:', error);
                    });

            });

            // Autocomplete Search
            document.getElementById('search').addEventListener('input', function() {
                let query = this.value;
                if (query.length < 3) {
                    document.getElementById('autocomplete-results').classList.add('hidden');
                    return;
                }

                fetch(`/api/search?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let results = data.results || [];
                        let resultsContainer = document.getElementById('autocomplete-results');
                        resultsContainer.innerHTML = '';

                        results.forEach(item => {
                            let div = document.createElement('div');
                            div.classList.add('px-4', 'py-2', 'hover:bg-gray-700', 'cursor-pointer');
                            div.textContent = item.title;
                            div.addEventListener('click', function() {
                                document.getElementById('search').value = item.title;
                                resultsContainer.classList.add('hidden');
                            });
                            resultsContainer.appendChild(div);
                        });

                        // Show results below the search input
                        resultsContainer.classList.remove('hidden');
                    })
                    .catch(error => console.error('Error fetching autocomplete results:', error));
            });

            // Hide autocomplete when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('#search') && !event.target.closest('#autocomplete-results')) {
                    document.getElementById('autocomplete-results').classList.add('hidden');
                }
            });
        </script>
    </div>
    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.6s ease-in-out forwards;
        }
    </style>
@endsection
