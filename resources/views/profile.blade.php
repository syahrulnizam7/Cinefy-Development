@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-3xl font-semibold text-gray-800 mb-8 text-center">Profile</h2>

            <div class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Name</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Joined</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $user->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="" class="text-blue-500 hover:underline text-lg">Edit Profile</a>
            </div>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Watched List</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($watched as $item)
                <div class="bg-gray-800 p-2 rounded-lg">
                    <img src="https://image.tmdb.org/t/p/w500{{ $item->poster_path }}" alt="{{ $item->title }}" class="rounded-lg">
                    <h3 class="text-white text-center mt-2">{{ $item->title }}</h3>
                    <p class="text-gray-500 text-center text-sm">{{ \Carbon\Carbon::parse($item->release_date)->format('d M Y') }}</p>
                    <p class="mt-1 -mb-1 text-xs bg-blue-500 px-2 py-1 inline-block rounded">
                        {{ $item->vote_average * 10 }}%
                    </p>
                    
                </div>
            @endforeach
        </div>
    </div>
@endsection
