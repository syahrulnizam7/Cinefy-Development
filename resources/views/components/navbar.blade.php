{{-- Navigasi TOP Menu --}}
<nav id="navbar"
class="fixed z-20 top-0 left-0 w-full py-6 px-6 bg-gradient-to-r from-gray-900 to-black text-white transition-all duration-300"
x-data="{ navOpen: true }">
<div class="container mx-auto flex justify-between items-center">
    <a href="/"><img src="{{ asset('images/logo_fordarktheme.png') }}" alt="My Image" class="h-16 order-1 sm:order-2"></a>
    <button @click ="navOpen =! navOpen" id="hamburger" name="hamburger" type="button"
        class="hover:bg-blue-700 transition bg-blue-600 rounded-md w-12 h-12 flex flex-col items-center justify-center gap-1.5 order-2 sm:order-1 lg:hidden">
        <span class="w-6 h-[2px] bg-white"></span>
        <span class="w-6 h-[2px] bg-white"></span>
        <span class="w-6 h-[2px] bg-white"></span>
    </button>
    <div class="order-3 hidden sm:block">
        @auth
            <!-- Tombol untuk menampilkan dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-white font-semibold">
                    {{ Auth::user()->name }}
                </button>
                <!-- Dropdown menu -->
                <div x-show="open" @click.outside="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white shadow-md rounded-md z-10">
                    <ul>
                        <li>
                            <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat Profil</a>
                        </li>
                        <li>
                            <!-- Logout button -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @else
            {{-- <a href="{{ url('login/google') }}" --}}
            <a href="{{ route('login') }}"
                class="grow bg-blue-600 text-white px-8 py-4 font-bold rounded-full text-sm hover:bg-blue-700 transition">Login</a>
            <a href="{{ url('login/google') }}"
                class="grow bg-blue-800 text-white px-8 py-4 font-bold rounded-full text-sm hover:bg-blue-700 transition">Sign
                Up</a>
        @endauth
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