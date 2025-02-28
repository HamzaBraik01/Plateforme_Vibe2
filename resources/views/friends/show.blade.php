@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Messages de succès ou d'erreur -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- En-tête du profil -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <img src="{{ $profileUser->avatar_url }}"
                class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md">
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-900">{{ $profileUser->name }}</h1>
                <p class="text-gray-600 text-lg">@ {{ $profileUser->pseudo }}</p>
                @if ($profileUser->bio)
                    <p class="text-gray-700 mt-2 text-base">{{ $profileUser->bio }}</p>
                @endif
                @if ($profileUser->isOnline())
                    <div class="text-sm text-green-600 flex items-center justify-center md:justify-start mt-2">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> En ligne
                    </div>
                @endif
            </div>
            <!-- Boutons d'action (si ce n'est pas votre propre profil) -->
            @if ($profileUser->id !== Auth::user()->id)
            <div class="flex gap-3">
                @if (Auth::user()->isFriendWith($profileUser->id))
                <form action="{{ route('friends.remove', $profileUser->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="bg-gray-200 text-gray-700 px-5 py-2 rounded-full hover:bg-gray-300 transition duration-200 text-sm font-semibold">
                        Retirer l'ami
                    </button>
                </form>
                @elseif (Auth::user()->hasPendingRequestTo($profileUser->id))
                <form action="{{ route('friends.cancel', $profileUser->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="bg-gray-200 text-gray-700 px-5 py-2 rounded-full hover:bg-gray-300 transition duration-200 text-sm font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Annuler
                    </button>
                </form>
                @else
                <form action="{{ route('friends.request', $profileUser->id) }}" method="POST">
                    @csrf
                    <button class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition duration-200 text-sm font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Tabs pour les sections -->
    <div class="flex justify-start gap-6 mb-6 border-b border-gray-200">
        <button class="tab-button px-4 py-2 text-gray-700 font-semibold border-b-2 border-transparent hover:border-blue-500 focus:outline-none active" data-tab="posts">Publications</button>
        <button class="tab-button px-4 py-2 text-gray-700 font-semibold border-b-2 border-transparent hover:border-blue-500 focus:outline-none" data-tab="liked">Publications aimées</button>
        <button class="tab-button px-4 py-2 text-gray-700 font-semibold border-b-2 border-transparent hover:border-blue-500 focus:outline-none" data-tab="comments">Commentaires</button>
    </div>

    <!-- Contenu des tabs -->
    <div id="posts" class="tab-content space-y-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Publications</h2>
        @forelse($profileUser->posts as $post)
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $profileUser->avatar_url }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <a href="{{ route('friends.show', $profileUser->id) }}"
                            class="font-semibold text-gray-900 hover:text-blue-600">{{ $profileUser->name }}</a>
                        <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @if ($post->user_id === Auth::user()->id)
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 border border-gray-100">
                        <a href="{{ route('posts.edit', $post) }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette publication ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <p class="text-gray-800 mb-4">{{ $post->content }}</p>

            @if ($post->photo)
            <img src="{{ $post->photo_url }}" class="w-full rounded-lg mb-4 object-cover" alt="Post image">
            @endif

            <div class="flex items-center justify-between text-gray-600 text-sm border-t pt-2 mb-4">
                <span class="flex items-center gap-1">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933c-.248.304-.424.686-.424.967z"></path>
                    </svg>
                    {{ $post->likes->count() }} J'aime
                </span>
                <span>{{ $post->commentaires->count() }} Commentaire(s)</span>
            </div>

            <div class="flex items-center gap-6 mb-4 border-t pt-2">
                <form action="{{ route('posts.like', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition duration-200">
                        <svg class="w-6 h-6 {{ $post->isLikedByUser(Auth::id()) ? 'text-blue-600 fill-current' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 4h4m12 0V4m0 4h-4M4 20v-8h4m12 0v8h-4m-8-12v12"></path>
                        </svg>
                        J'aime
                    </button>
                </form>
                <button type="button" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4v8"></path>
                    </svg>
                    Commenter
                </button>
            </div>

            <div class="space-y-4">
                @forelse($post->commentaires as $commentaire)
                <div class="flex items-start gap-3">
                    <img src="{{ $commentaire->user->avatar_url }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div class="bg-gray-100 rounded-lg p-3 flex-1">
                        <a href="{{ route('friends.show', $commentaire->user->id) }}"
                            class="font-semibold text-gray-900 hover:text-blue-600">{{ $commentaire->user->name }}</a>
                        <p class="text-gray-800 text-sm">{{ $commentaire->content }}</p>
                        <span class="text-xs text-gray-500">{{ $commentaire->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Aucun commentaire pour le moment.</p>
                @endforelse

                <form action="{{ route('posts.comment', $post) }}" method="POST" class="flex items-start gap-3 mt-4">
                    @csrf
                    <img src="{{ Auth::user()->avatar_url }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div class="flex-1">
                        <input type="text" name="content"
                            class="w-full p-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400"
                            placeholder="Écrire un commentaire...">
                        @error('content')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="text-blue-600 hover:text-blue-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center py-4">Aucune publication pour cet utilisateur.</p>
        @endforelse
    </div>

    <!-- Section des publications aimées -->
    <div id="liked" class="tab-content space-y-6 hidden">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Publications aimées</h2>
        @forelse($profileUser->likes as $like)
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $like->post->user->avatar_url }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <a href="{{ route('friends.show', $like->post->user->id) }}"
                            class="font-semibold text-gray-900 hover:text-blue-600">{{ $like->post->user->name }}</a>
                        <div class="text-sm text-gray-500">{{ $like->post->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @if ($like->post->user_id === Auth::user()->id)
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 border border-gray-100">
                        <a href="{{ route('posts.edit', $like->post) }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</a>
                        <form action="{{ route('posts.destroy', $like->post) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette publication ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <p class="text-gray-800 mb-4">{{ $like->post->content }}</p>

            @if ($like->post->photo)
            <img src="{{ $like->post->photo_url }}" class="w-full rounded-lg mb-4 object-cover" alt="Post image">
            @endif

            <div class="flex items-center justify-between text-gray-600 text-sm border-t pt-2 mb-4">
                <span class="flex items-center gap-1">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933c-.248.304-.424.686-.424.967z"></path>
                    </svg>
                    {{ $like->post->likes->count() }} J'aime
                </span>
                <span>{{ $like->post->commentaires->count() }} Commentaire(s)</span>
            </div>

            <div class="flex items-center gap-6 mb-4 border-t pt-2">
                <form action="{{ route('posts.like', $like->post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition duration-200">
                        <svg class="w-6 h-6 {{ $like->post->isLikedByUser(Auth::id()) ? 'text-blue-600 fill-current' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 4h4m12 0V4m0 4h-4M4 20v-8h4m12 0v8h-4m-8-12v12"></path>
                        </svg>
                        J'aime
                    </button>
                </form>
                <button type="button" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4v8"></path>
                    </svg>
                    Commenter
                </button>
            </div>

            <div class="space-y-4">
                @forelse($like->post->commentaires as $commentaire)
                <div class="flex items-start gap-3">
                    <img src="{{ $commentaire->user->avatar_url }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div class="bg-gray-100 rounded-lg p-3 flex-1">
                        <a href="{{ route('friends.show', $commentaire->user->id) }}"
                            class="font-semibold text-gray-900 hover:text-blue-600">{{ $commentaire->user->name }}</a>
                        <p class="text-gray-800 text-sm">{{ $commentaire->content }}</p>
                        <span class="text-xs text-gray-500">{{ $commentaire->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Aucun commentaire pour le moment.</p>
                @endforelse

                <form action="{{ route('posts.comment', $like->post) }}" method="POST" class="flex items-start gap-3 mt-4">
                    @csrf
                    <img src="{{ Auth::user()->avatar_url }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div class="flex-1">
                        <input type="text" name="content"
                            class="w-full p-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400"
                            placeholder="Écrire un commentaire...">
                        @error('content')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="text-blue-600 hover:text-blue-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center py-4">Aucune publication aimée par cet utilisateur.</p>
        @endforelse
    </div>

    <!-- Section des commentaires ajoutés -->
    <div id="comments" class="tab-content space-y-6 hidden">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Commentaires ajoutés</h2>
        @php
            // Grouper les commentaires par post pour éviter les duplications
            $commentedPosts = $profileUser->commentaires->groupBy('post_id');
        @endphp
        @forelse($commentedPosts as $postId => $comments)
        @php
            $post = $comments->first()->post;
        @endphp
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $post->user->avatar_url }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <a href="{{ route('friends.show', $post->user->id) }}"
                            class="font-semibold text-gray-900 hover:text-blue-600">{{ $post->user->name }}</a>
                        <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @if ($post->user_id === Auth::user()->id)
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 border border-gray-100">
                        <a href="{{ route('posts.edit', $post) }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette publication ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <p class="text-gray-800 mb-4">{{ $post->content }}</p>

            @if ($post->photo)
            <img src="{{ $post->photo_url }}" class="w-full rounded-lg mb-4 object-cover" alt="Post image">
            @endif

            <!-- Commentaires de l'utilisateur -->
            <div class="bg-gray-50 rounded-lg p-4 mb-4 space-y-3">
                @foreach($comments as $comment)
                <div class="flex items-start gap-3">
                    <img src="{{ $profileUser->avatar_url }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div class="bg-gray-100 rounded-lg p-3 flex-1">
                        <a href="{{ route('friends.show', $profileUser->id) }}"
                            class="font-semibold text-gray-900 hover:text-blue-600">{{ $profileUser->name }}</a>
                        <p class="text-gray-800 text-sm">{{ $comment->content }}</p>
                        <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between text-gray-600 text-sm border-t pt-2 mb-4">
                <span class="flex items-center gap-1">
                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933c-.248.304-.424.686-.424.967z"></path>
                    </svg>
                    {{ $post->likes->count() }} J'aime
                </span>
                <span>{{ $post->commentaires->count() }} Commentaire(s)</span>
            </div>

            <div class="flex items-center gap-6 mb-4 border-t pt-2">
                <form action="{{ route('posts.like', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition duration-200">
                        <svg class="w-6 h-6 {{ $post->isLikedByUser(Auth::id()) ? 'text-blue-600 fill-current' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 4h4m12 0V4m0 4h-4M4 20v-8h4m12 0v8h-4m-8-12v12"></path>
                        </svg>
                        J'aime
                    </button>
                </form>
                <button type="button" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4v8"></path>
                    </svg>
                    Commenter
                </button>
            </div>

            <div class="space-y-4">
                @forelse($post->commentaires as $postComment)
                @if (!$comments->contains('id', $postComment->id)) <!-- Exclure les commentaires déjà affichés -->
                <div class="flex items-start gap-3">
                    <img src="{{ $postComment->user->avatar_url }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div class="bg-gray-100 rounded-lg p-3 flex-1">
                        <a href="{{ route('friends.show', $postComment->user->id) }}"
                            class="font-semibold text-gray-900 hover:text-blue-600">{{ $postComment->user->name }}</a>
                        <p class="text-gray-800 text-sm">{{ $postComment->content }}</p>
                        <span class="text-xs text-gray-500">{{ $postComment->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endif
                @empty
                <p class="text-gray-500 text-sm">Aucun autre commentaire.</p>
                @endforelse

                <form action="{{ route('posts.comment', $post) }}" method="POST" class="flex items-start gap-3 mt-4">
                    @csrf
                    <img src="{{ Auth::user()->avatar_url }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div class="flex-1">
                        <input type="text" name="content"
                            class="w-full p-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400"
                            placeholder="Écrire un commentaire...">
                        @error('content')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="text-blue-600 hover:text-blue-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center py-4">Aucun commentaire ajouté par cet utilisateur.</p>
        @endforelse
    </div>
</div>

<!-- JavaScript pour gérer les onglets -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.tab-button');
        const contents = document.querySelectorAll('.tab-content');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                // Retirer la classe active de tous les boutons
                buttons.forEach(btn => {
                    btn.classList.remove('border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-700');
                });
                // Ajouter la classe active au bouton cliqué
                this.classList.add('border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-700');

                // Masquer tout le contenu
                contents.forEach(content => content.classList.add('hidden'));
                // Afficher le contenu correspondant
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });

        // Afficher la première tab par défaut
        document.querySelector('.tab-button').click();
    });
</script>
@endsection
