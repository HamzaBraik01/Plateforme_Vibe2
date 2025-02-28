@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

    <!-- Barre de recherche -->
    <div class="mb-8">
        <form method="GET" class="flex items-center gap-3">
            <input type="text" name="search" value="{{ $search }}"
                class="w-full p-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none shadow-sm placeholder-gray-400"
                placeholder="Rechercher des amis par nom, email ou pseudo...">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition duration-200 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Grille principale -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Demandes d'amis reçues -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Demandes d'amis reçues</h2>
            @forelse($friendRequests as $request)
            <div class="flex items-center justify-between py-4 border-b last:border-b-0">
                <div class="flex items-center gap-4">
                    <img src="{{ $request->sender->avatar_url }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <a href="#" class="font-semibold text-gray-900 hover:text-blue-600">{{ $request->sender->name }}</a>
                        <div class="text-sm text-gray-600">@ {{ $request->sender->pseudo }}</div>
                        @if ($request->sender->mutualFriendsCount() > 0)
                            <div class="text-xs text-gray-500">{{ $request->sender->mutualFriendsCount() }} ami(s) en commun</div>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('friends.accept', $request->id) }}" method="POST">
                        @csrf
                        <button class="bg-blue-600 text-white px-4 py-1 rounded-full hover:bg-blue-700 transition duration-200 text-sm font-medium">
                            Accepter
                        </button>
                    </form>
                    <form action="{{ route('friends.reject', $request->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="bg-gray-200 text-gray-700 px-4 py-1 rounded-full hover:bg-gray-300 transition duration-200 text-sm font-medium">
                            Refuser
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucune demande en attente</p>
            @endforelse
        </div>

        <!-- Liste des amis -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Mes amis</h2>
            @forelse($friends as $friend)
            <div class="flex items-center justify-between py-4 border-b last:border-b-0">
                <div class="flex items-center gap-4">
                    <img src="{{ $friend->avatar_url }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <a href="#" class="font-semibold text-gray-900 hover:text-blue-600">{{ $friend->name }}</a>
                        <div class="text-sm text-gray-600">@ {{ $friend->pseudo }}</div>
                        @if ($friend->isOnline())
                            <div class="text-xs text-green-500 flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> En ligne
                            </div>
                        @endif
                    </div>
                </div>
                <form action="{{ route('friends.remove', $friend->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 hover:text-red-700 text-sm font-medium">
                        Supprimer
                    </button>
                </form>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucun ami pour le moment</p>
            @endforelse
        </div>

        <!-- Suggestions d'amis -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Suggestions d'amis</h2>
            @forelse($users as $user)
            <div class="flex items-center justify-between py-4 border-b last:border-b-0">
                <div class="flex items-center gap-4">
                    <img src="{{ $user->avatar_url }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <a href="#" class="font-semibold text-gray-900 hover:text-blue-600">{{ $user->name }}</a>
                        <div class="text-sm text-gray-600">@ {{ $user->pseudo }}</div>
                        @if ($user->mutualFriendsCount() > 0)
                            <div class="text-xs text-gray-500">{{ $user->mutualFriendsCount() }} ami(s) en commun</div>
                        @endif
                    </div>
                </div>
                <form action="{{ route('friends.request', $user->id) }}" method="POST">
                    @csrf
                    <button class="bg-blue-600 text-white px-4 py-1 rounded-full hover:bg-blue-700 transition duration-200 text-sm font-medium flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter
                    </button>
                </form>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucun utilisateur trouvé</p>
            @endforelse
            <div class="mt-4">
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
