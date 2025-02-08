<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    @vite('resources/css/app.css')


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
