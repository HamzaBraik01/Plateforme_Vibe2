@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Messages -->
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

    <!-- Formulaire de nouvelle publication -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="flex items-start gap-4">
                <img src="{{ Auth::user()->avatar_url }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                <div class="flex-1">
                    <textarea name="content" rows="3"
                            class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400 resize-none"
                            placeholder="Quoi de neuf, {{ Auth::user()->name }} ?"></textarea>
                    @error('content')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <input type="file" name="photo"
                    class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition duration-200">
                    Publier
                </button>
            </div>
            @error('photo')
                <span class="text-red-600 text-sm">{{ $message }}</span>
            @enderror
        </form>
    </div>

    <!-- Liste des publications -->
    @forelse($posts as $post)
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
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
            @if ($post->user_id === Auth::id())
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
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

        <!-- Likes et Commentaires -->
        <div class="flex items-center justify-between text-gray-600 text-sm border-t pt-2 mb-4">
            <span class="flex items-center gap-1">
                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933c-.248.304-.424.686-.424.967z"></path>
                </svg>
                {{ $post->likes->count() }} J'aime
            </span>
            <span>{{ $post->commentaires->count() }} Commentaire(s)</span>
        </div>

        <!-- Boutons Like et Commenter -->
        <div class="flex items-center gap-4 mb-4">
            <!-- Bouton J'aime -->
            <form action="{{ route('posts.like', $post) }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-gray-600 hover:text-blue-500 transition duration-200">
                    <svg class="w-5 h-5 {{ $post->isLikedByUser(Auth::id()) ? 'text-blue-500 fill-current' : '' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 4h4m12 0V4m0 4h-4M4 20v-8h4m12 0v8h-4m-8-12v12"></path>
                    </svg>
                    J'aime
                </button>
            </form>

            <!-- Bouton Commenter -->
            <button type="button" class="flex items-center gap-2 text-gray-600 hover:text-blue-600 transition duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 10h8M8 14h6M4 18v-8a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v8l-4-2H8l-4 2Z"/>
                </svg>
                Commenter
            </button>
        </div>

        <!-- Commentaires -->
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

            <!-- Formulaire de commentaire -->
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
                    @endforelse
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
    <p class="text-gray-500 text-center">Aucune publication pour le moment.</p>
    @endforelse

    <div class="mt-6">
        {{ $posts->links('pagination::tailwind') }}
    </div>
</div>
@endsection
