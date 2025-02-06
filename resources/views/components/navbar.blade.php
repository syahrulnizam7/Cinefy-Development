<nav id="navbar"
    class="fixed z-20 top-0 left-0 w-full py-6 px-6 bg-gradient-to-b from-gray-900 to-transparent text-white transition-all duration-300"
    x-data="{
        navOpen: true,
        searchOpen: false,
        query: '',
        results: [],
        performSearch() {
            if (this.query.trim().length < 3) {
                this.results = [];
                return;
            }
            fetch(`/api/search?q=${this.query}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data.results); // Debugging output
                    // Filter results to exclude 'person' type
                    this.results = data.results.filter(item => item.media_type !== 'person')
                        .map(item => ({
                            id: item.id,
                            title: item.title || item.name,
                            release_date: item.release_date || item.first_air_date || '',
                            type: item.type,
                            // Jika ada poster_path, gabungkan dengan URL TMDB
                            // Jika tidak ada poster_path, gunakan gambar fallback
                            poster_path: item.poster_path ?
                                `https://image.tmdb.org/t/p/w92${item.poster_path}` : '/images/noimg.png',
                            average_rating: typeof item.average_rating === 'number' ? item.average_rating.toFixed(1) : 'N/A'
                        }));
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    
    }">

    <div class="container w-full mx-auto flex justify-between items-center">
        <a href="/"><img src="{{ asset('images/logomwl.png') }}" alt="My Image" class="h-12 order-1 sm:order-2"></a>
        <button @click ="navOpen =! navOpen" id="hamburger" name="hamburger" type="button"
            class="hover:bg-blue-700 transition bg-blue-600 rounded-md w-12 h-12 flex flex-col items-center justify-center gap-1.5 order-2 sm:order-1 lg:hidden">
            <span class="w-6 h-[2px] bg-white"></span>
            <span class="w-6 h-[2px] bg-white"></span>
            <span class="w-6 h-[2px] bg-white"></span>
        </button>
        <div class="order-3 hidden sm:block">
            @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-white font-semibold flex items-center gap-2">
                        <!-- Profile Photo -->
                        <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/default-avatar.png') }}"
                            alt="Profile" class="w-10 h-10 rounded-full object-cover border-2 border-white">
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.outside="open = false"
                        class="absolute right-0 mt-2 w-48 bg-gray-800 text-white shadow-lg rounded-xl border border-gray-700 z-10">
                        <ul class="py-2">
                            <!-- Profile Link -->
                            <li>
                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-md transition">Lihat Profil</a>
                            </li>
                            <!-- Logout -->
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-700 rounded-md transition">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="grow bg-blue-600 text-white px-8 py-4 font-bold rounded-full text-sm hover:bg-blue-700 transition">Login</a>
                <a href="{{ url('login/google') }}"
                    class="grow bg-blue-800 text-white px-8 py-4 font-bold rounded-full text-sm hover:bg-blue-700 transition">Sign
                    Up</a>
            @endauth
        </div>

        <div class="hidden lg:block order-2">
            <ul class="flex gap-16">
                <li class="text-white font-bold text-sm hover:text-blue-600"><a href="/">Home</a></li>
                <li>
                    <button @click="searchOpen = true"
                        class="text-white font-bold text-sm hover:text-blue-600">Search</button>
                </li>
                <li class="text-white font-bold text-sm hover:text-blue-600"><a href="">Add</a></li>
                <li class="text-white font-bold text-sm hover:text-blue-600"><a href="">Activity</a></li>
            </ul>
        </div>
    </div>

    <!-- Search Modal -->
    <div x-show="searchOpen"
        class="fixed inset-0 bg-gradient-to-b from-gray-900/80 to-black/80 backdrop-blur-md z-30 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <!-- Modal Content -->
        <div @click.away="searchOpen = false"
            class="bg-gray-800 p-6 rounded-2xl w-full max-w-md shadow-lg transform transition-all duration-300"
            x-transition:enter="transition transform ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition transform ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">

            <!-- Input Box -->
            <input type="text" id="searchInput" placeholder="Search movies, series, and more..." x-model="query"
                @input.debounce.300ms="performSearch"
                class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400 text-lg">

            <!-- Results Container -->
            <div x-show="results.length > 0" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                class="mt-4 bg-gray-900 text-white shadow-inner max-h-60 overflow-auto scrollbar-hidden rounded-lg">

                <template x-for="result in results" :key="result.id">
                    <a :href="'/detail/' + result.type + '/' + result.id"
                        class="flex items-center p-4 hover:bg-gray-700 transition duration-200 rounded-md">
                        <img :src="result.poster_path ? `https://image.tmdb.org/t/p/w92${result.poster_path}` : '/images/noimg.png'"
                            alt="Poster" class="w-16 h-24 object-cover mr-4 rounded-md">

                        <div>
                            <p class="font-bold text-lg" x-text="result.title"></p>
                            <p class="text-sm text-gray-400" x-text="result.release_date"></p>
                            <p class="text-sm text-yellow-400 font-semibold">
                                <span x-text="'Rating: ' + result.average_rating"></span>
                            </p>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </div>

    <div x-show="navOpen" class="fixed scale-75 rounded-full z-20 bottom-1 right-1 left-1 p-4 lg:hidden bg-blue-600 "
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
                <button @click="searchOpen = true" class="flex flex-col items-center gap-1 group-hover:text-blue-500">
                    <ion-icon name="search"
                        class="text-2xl text-white opacity-100 group-hover:text-blue-500 group-hover:opacity-100"></ion-icon>
                    <span
                        class="text-white opacity-100 text-base font-normal group-hover:text-blue-500 group-hover:opacity-100">Search</span>
                </button>
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
                <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1 group-hover:text-blue-500">
                    <ion-icon name="person"
                        class="text-2xl text-white opacity-100 group-hover:text-blue-500 group-hover:opacity-100"></ion-icon>
                    <span
                        class="text-white opacity-100 text-base font-normal group-hover:text-blue-500 group-hover:opacity-100">Profile</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
