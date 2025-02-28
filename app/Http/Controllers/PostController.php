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
        $user = Auth::user();
        // Récupérer les IDs des amis acceptés
        $friendIds = $user->friends()->pluck('users.id')->toArray();
        // Ajouter l'ID de l'utilisateur connecté pour inclure ses propres posts
        $friendIds[] = $user->id;

        // Récupérer les posts des amis acceptés et de l'utilisateur, avec leurs relations
        $posts = Post::with('user', 'commentaires.user', 'likes')
            ->whereIn('user_id', $friendIds) // Filtrer pour inclure seulement les amis et soi-même
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
        // Vérifier que l'utilisateur est l'auteur du post
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('posts.index')->with('error', 'Vous ne pouvez pas modifier cette publication.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Vérifier que l'utilisateur est l'auteur du post
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
        // Vérifier que l'utilisateur est l'auteur du post
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
        // Valider le contenu du commentaire
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        // Créer un nouveau commentaire
        Commentaire::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $post->id_pub,
        ]);

        return redirect()->route('posts.index')->with('success', 'Commentaire ajouté avec succès !');
    }

    // Nouvelle méthode pour supprimer un commentaire
    public function deleteComment(Commentaire $comment)
    {
        // Vérifier que le commentaire appartient à l'utilisateur connecté
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire.');
        }

        // Vérifier que le post appartient à l'utilisateur ou à un ami accepté
        $friendIds = Auth::user()->friends()->pluck('users.id')->toArray();
        $friendIds[] = Auth::id(); // Inclure l'utilisateur connecté
        if (!in_array($comment->post->user_id, $friendIds)) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire dans cette publication.');
        }

        // Supprimer le commentaire
        $comment->delete();

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès !');
    }

    // Nouvelle méthode pour modifier un commentaire
    public function updateComment(Request $request, Commentaire $comment)
    {
        // Vérifier que le commentaire appartient à l'utilisateur connecté
        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier ce commentaire.');
        }

        // Vérifier que le post appartient à l'utilisateur ou à un ami accepté
        $friendIds = Auth::user()->friends()->pluck('users.id')->toArray();
        $friendIds[] = Auth::id(); // Inclure l'utilisateur connecté
        if (!in_array($comment->post->user_id, $friendIds)) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier ce commentaire dans cette publication.');
        }

        // Valider le contenu modifié
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        // Mettre à jour le commentaire
        $comment->content = $request->content;
        $comment->save();

        return redirect()->back()->with('success', 'Commentaire modifié avec succès !');
    }
}
