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

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Modifier la publication</h1>

        <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <textarea name="content" rows="5"
                        class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none placeholder-gray-400 resize-none"
                        placeholder="Modifiez votre contenu ici...">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                @if ($post->photo)
                <img src="{{ $post->photo_url }}" class="w-32 h-32 rounded-lg mb-2 object-cover" alt="Current image">
                <p class="text-gray-600 text-sm mb-2">Image actuelle</p>
                @endif
                <input type="file" name="photo"
                    class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('photo')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition duration-200">
                    Mettre à jour
                </button>
                <a href="{{ route('posts.index') }}"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-300 transition duration-200">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
