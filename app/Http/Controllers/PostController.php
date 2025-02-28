<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Commentaire;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'commentaires.user', 'likes')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ]);

        $post = new Post();
        $post->content = $request->content;
        $post->user_id = Auth::id();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('posts', 'public');
            $post->photo = $path;
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Publication ajoutée avec succès !');
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', 'Vous ne pouvez pas modifier cette publication.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', 'Vous ne pouvez pas modifier cette publication.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ]);

        $post->content = $request->content;

        if ($request->hasFile('photo')) {
            if ($post->photo) {
                Storage::delete('public/' . $post->photo);
            }
            $path = $request->file('photo')->store('posts', 'public');
            $post->photo = $path;
        }

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Publication mise à jour avec succès !');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', 'Vous ne pouvez pas supprimer cette publication.');
        }

        if ($post->photo) {
            Storage::delete('public/' . $post->photo);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Publication supprimée avec succès !');
    }

    public function like(Post $post)
    {
        $userId = Auth::id();

        if ($post->isLikedByUser($userId)) {
            $post->likes()->where('user_id', $userId)->delete();
            return redirect()->route('posts.index')->with('success', 'Like retiré.');
        }

        Like::create([
            'user_id' => $userId,
            'post_id' => $post->id_pub,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post liké !');
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Commentaire::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $post->id_pub,
        ]);

        return redirect()->route('posts.index')->with('success', 'Commentaire ajouté avec succès !');
    }
}
