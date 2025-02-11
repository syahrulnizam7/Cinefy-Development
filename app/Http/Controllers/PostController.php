<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\PostImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'images'])->latest()->get();
        return view('posts', compact('posts'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280',
            'images.*' => 'nullable' // Validasi banyak gambar
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        if ($request->has('images')) {
            foreach ($request->images as $image) {
                // Decode JSON jika data berasal dari ShowShareModal
                $imageData = json_decode($image, true);

                if (is_array($imageData) && isset($imageData['image'])) {
                    unset($imageData['overview']); // Hapus overview sebelum disimpan
                    
                    PostImage::create([
                        'post_id' => $post->id,
                        'image_path' => json_encode($imageData) // Simpan dalam bentuk JSON
                    ]);
                } elseif (filter_var($image, FILTER_VALIDATE_URL)) {
                    PostImage::create([
                        'post_id' => $post->id,
                        'image_path' => $image // Simpan URL langsung
                    ]);
                } elseif ($request->hasFile('images')) {
                    $imagePath = $image->store('posts', 'public');
                    PostImage::create([
                        'post_id' => $post->id,
                        'image_path' => $imagePath
                    ]);
                }
            }
        }

        return redirect('/posts')->with('success', 'Post berhasil dibuat!');
    }


    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return back()->with('error', 'Kamu tidak memiliki izin untuk menghapus postingan ini.');
        }

        // Hapus gambar terkait jika ada
        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $post->delete();

        return back()->with('success', 'Post berhasil dihapus.');
    }
}
