<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">

    <div class="container mx-auto p-4">
        <!-- Back Button -->
        <a href="{{ url('/') }}" class="text-blue-500 mb-4 inline-block">Back to Movies</a>

        <!-- Movie Details -->
        <div class="flex flex-col md:flex-row items-center bg-white rounded-lg shadow-md p-6">
            <!-- Poster Image -->
            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="Poster" class="w-48 h-72 object-cover rounded-md">

            <div class="ml-6 mt-4 md:mt-0">
                <h1 class="text-3xl font-bold">{{ $movie['title'] }}</h1>
                <p class="text-gray-600 text-sm mt-2">{{ Str::limit($movie['overview'], 150) }}</p>

                <div class="mt-4 text-sm text-gray-500">
                    <p><strong>Release Date:</strong> {{ \Carbon\Carbon::parse($movie['release_date'])->format('F d, Y') }}</p>
                    <p><strong>Rating:</strong> {{ $movie['vote_average'] }} / 10</p>
                    
                </div>
            </div>
        </div>
    </div>

</body>
</html>
