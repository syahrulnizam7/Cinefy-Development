@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6 mt-10">

        <div x-data="{ showNotification: false, message: '' }" x-show="showNotification" x-transition.duration.500ms
            class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
            <p x-text="message"></p>
        </div>


        <!-- List Posts -->
        <div class="space-y-8">
            @foreach ($posts as $post)
                <div x-data="{ openDropdown: false }" class="border-b border-gray-700 p-6  shadow-md ">

                    <!-- User Info -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('user.detail', $post->user->id) }}">
                                <img src="{{ $post->user->profile_photo ? asset('storage/' . $post->user->profile_photo) : asset('default-avatar.png') }}"
                                    alt="Avatar" class="w-12 h-12 rounded-full border-2 border-blue-500 object-cover">
                            </a>
                            <div>
                                <a href="{{ route('user.detail', $post->user->id) }}"
                                    class="font-bold text-white text-lg hover:text-blue-400">{{ $post->user->name }}</a>
                                <p class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Menu Titik Tiga -->
                        <div class="relative ml-auto">
                            <button @click="openDropdown = !openDropdown"
                                class="text-gray-400 hover:text-white focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="3.5" stroke="currentColor" class="w-6 h-6 ">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 12h.008m5.992 0h.008m5.992 0h.008M6.75 12h.008m5.992 0h.008m5.992 0h.008" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="openDropdown" @click.away="openDropdown = false"
                                class="absolute right-0 mt-2 w-40 bg-gray-800 border border-gray-700 rounded-md shadow-lg z-50">
                                <a href="{{ route('user.detail', $post->user->id) }}"
                                    class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700">
                                    About This User
                                </a>

                                @if (auth()->id() === $post->user_id)
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-700">
                                            Delete Post
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>




                    <!-- Post Content -->
                    <div class="mt-2 text-gray-300">
                        <p>{{ $post->content }}</p>
                        @if ($post->images->count() > 0)
                            <div
                                class="mt-4 
        @if ($post->images->count() === 1) flex justify-center 
        @elseif ($post->images->count() === 2) 
            grid grid-cols-2 gap-2
        @elseif ($post->images->count() === 3) 
            grid grid-cols-3 gap-2
        @else 
            grid grid-cols-2 md:grid-cols-3 gap-2 @endif">
                                @foreach ($post->images as $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Post Image"
                                        class="w-full max-h-96 object-cover rounded-lg shadow-md">
                                @endforeach
                            </div>
                        @endif


                    </div>

                    <!-- Post Actions -->
                    <div class="flex items-center justify-between mt-4 text-gray-400">
                        <!-- Like Button with Alpine.js -->
                        <div x-data="likeComponent({{ $post->id }}, {{ $post->isLikedBy(auth()->id()) ? 'true' : 'false' }}, {{ $post->likes->count() }})" data-post-id="{{ $post->id }}">
                            <button @click="toggleLike" class="flex items-center space-x-1"
                                :class="liked ? 'text-red-500' : 'text-gray-500'">
                                <svg xmlns="http://www.w3.org/2000/svg" :fill="liked ? 'red' : 'none'" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.828 9.172a4 4 0 015.657 5.656l-6.343 6.343a1 1 0 01-1.414 0l-6.343-6.343a4 4 0 115.657-5.656z" />
                                </svg>
                                <span x-text="likeCount"></span>
                            </button>
                        </div>

                        <!-- Comments -->
                        <button class="flex items-center space-x-1 hover:text-blue-500"
                            onclick="toggleComments('{{ $post->id }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 12h9M9 15h6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $post->comments->count() }}</span>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div id="comments-{{ $post->id }}" class="hidden mt-4">
                        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-4">
                            @csrf
                            <textarea name="content" rows="2" class="w-full p-2 border rounded focus:ring focus:ring-blue-300"
                                placeholder="Tulis komentar..."></textarea>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-2 rounded">Kirim</button>
                        </form>

                        <div class="space-y-2">
                            @foreach ($post->comments as $comment)
                                <div class="flex items-start space-x-2">
                                    <!-- Klik gambar menuju detail pengguna -->
                                    <a href="{{ route('user.detail', $comment->user->id) }}">
                                        <img src="{{ $comment->user->profile_photo ? asset('storage/' . $comment->user->profile_photo) : asset('default-avatar.png') }}"
                                            alt="Avatar" class="w-10 h-10 rounded-full">
                                    </a>
                                    <div>
                                        <!-- Klik nama menuju detail pengguna -->
                                        <a href="{{ route('user.detail', $comment->user->id) }}"
                                            class="font-bold hover:underline">
                                            {{ $comment->user->name }}
                                        </a>
                                        <p>{{ $comment->content }}</p>
                                        <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div x-data="{ showMoreModal: false, showPostModal: false }">
        <!-- Floating Button -->
        <button @click="
            @guest showMoreModal = true; @else showPostModal = true; @endguest"
            class="fixed bottom-6 right-6 bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </button>

        <!-- Modal More (Untuk User Belum Login) -->
        @guest
            <div x-show="showMoreModal" x-cloak @click.away="showMoreModal = false"
                class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-80">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-80 text-center" @click.stop>
                    <!-- Mencegah klik di dalam modal menutupnya -->
                    <h2 class="text-white text-lg font-semibold mb-4">You Are Not Logged In</h2>
                    <p class="text-gray-400 text-sm mb-6">Please log in to create a post.</p>
                    <a href="{{ route('login') }}"
                        class="block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mb-3 transition">
                        Login
                    </a>
                    <button @click="showMoreModal = false"
                            class="w-full block bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Later</button>
                    </a>
                </div>
            </div>
        @endguest

        <!-- Modal untuk Membuat Post (User Sudah Login) -->
        @auth
            <div x-show="showPostModal" x-cloak @click.away="showPostModal = false"
                class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg w-96 shadow-lg">
                    <h3 class="text-lg font-bold mb-4">Buat Postingan Baru</h3>
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea name="content" rows="3" class="w-full p-2 border rounded focus:ring focus:ring-blue-300"
                            placeholder="Apa yang sedang kamu pikirkan?" required></textarea>
                        <input type="file" name="images[]" multiple class="mt-4">
                        <div class="mt-4 flex justify-end">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 mr-2 rounded"
                                @click="showPostModal = false">Batal</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth
    </div>




    <script>
        function toggleModal() {
            const modal = document.getElementById('post-modal');
            modal.classList.toggle('hidden');
        }

        function toggleComments(postId) {
            const comments = document.getElementById(`comments-${postId}`);
            comments.classList.toggle('hidden');
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('likeComponent', (postId, initialLiked, initialLikeCount) => ({
                liked: initialLiked,
                likeCount: initialLikeCount,

                toggleLike() {
                    fetch(`/posts/${postId}/toggle-like`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            this.liked = data.liked;
                            this.likeCount = data.likeCount;
                        })
                        .catch(error => console.error('Error:', error));
                }
            }));
        });
        
    </script>
@endsection
