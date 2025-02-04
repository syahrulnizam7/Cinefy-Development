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
@endsection
