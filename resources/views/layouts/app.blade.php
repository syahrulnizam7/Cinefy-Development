<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    
    @vite('resources/css/app.css')

    {{-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        .scrollbar-hidden {
            -ms-overflow-style: none;
            /* Untuk Internet Explorer 10+ */
            scrollbar-width: none;
            /* Untuk Firefox */
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
            /* Untuk Chrome, Safari, dan Edge */
        }
    </style> --}}

</head>

<body class="overflow-y-auto w-full relative overflow-hidden bg-gradient-to-r from-black via-gray-900 to-black  ">
    <div x-data="{ loading: false }" x-init="window.addEventListener('load', () => loading = false)">
        <div x-show="loading" id="loader">
            <div class="spinner"></div>
        </div>

        <!-- Navbar -->
        @include('components.navbar')

        <!-- Main Content (where individual page content will be inserted) -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('components.footer')


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const loader = document.getElementById("loader");
                loader.style.display = "flex"; // tampilkan loader saat loading halaman

                window.onload = () => {
                    loader.style.display = "none"; // sembunyikan loader setelah halaman selesai dimuat
                };
            });
        </script>

    </div>
</body>

</html>
