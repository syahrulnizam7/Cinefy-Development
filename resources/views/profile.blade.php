@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Profile</h2>

            <div class="mb-4">
                <label class="text-lg font-medium text-gray-600">Name:</label>
                <p class="text-gray-700">{{ $user->name }}</p>
            </div>

            <div class="mb-4">
                <label class="text-lg font-medium text-gray-600">Email:</label>
                <p class="text-gray-700">{{ $user->email }}</p>
            </div>

            <div class="mb-4">
                <label class="text-lg font-medium text-gray-600">Joined:</label>
                <p class="text-gray-700">{{ $user->created_at->format('d M Y') }}</p>
            </div>

            <div class="mb-4">
                <label class="text-lg font-medium text-gray-600">Last Updated:</label>
                <p class="text-gray-700">{{ $user->updated_at->format('d M Y') }}</p>
            </div>

            <!-- Tambahkan data lainnya sesuai kebutuhan -->
            <div class="mt-6">
                <a href="" class="text-blue-500 hover:underline">Edit Profile</a>
            </div>
        </div>
    </div>
@endsection
