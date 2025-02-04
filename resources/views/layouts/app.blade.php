<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
   
</head>
<body class="overflow-y-auto w-full overflow-hidden bg-gradient-to-r from-black via-gray-900 to-black  relative">


    <!-- Navbar -->
    @include('components.navbar')

    <!-- Main Content (where individual page content will be inserted) -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')
 
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
