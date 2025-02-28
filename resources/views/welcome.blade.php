<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Vibe - Votre Réseau Social</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Poppins', sans-serif; }
        </style>
    </head>
    <body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-blue-50 text-gray-800 antialiased">
        <!-- Header -->
        <header class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 bg-white/95 border-b-2 border-gray-200 shadow-sm">
            <!-- Nouveau Logo -->
            <svg class="h-14 w-32 transition-transform hover:scale-105" viewBox="0 0 140 70" xmlns="http://www.w3.org/2000/svg">
                <circle cx="35" cy="35" r="25" fill="none" stroke="#8b5cf6" stroke-width="6" stroke-linecap="round" stroke-dasharray="10 5"/>
                <path d="M50 50 Q70 20 90 50" stroke="#8b5cf6" stroke-width="6" fill="none" stroke-linecap="round"/>
                <text x="65" y="45" font-size="30" font-weight="700" fill="#1f2937" transform="rotate(-10 65 45)">Vibe</text>
            </svg>

            <!-- Auth Links -->
            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-white hover:bg-purple-500 px-3 py-2 rounded-md transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-white hover:bg-purple-500 px-3 py-2 rounded-md transition-colors">Se connecter</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="font-semibold text-gray-600 hover:text-white hover:bg-purple-500 px-3 py-2 rounded-md transition-colors">S'inscrire</a>
                        @endif
                    @endauth
                </div>
            @endif
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Hero -->
            <div class="text-center mb-16">
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900">Vibrez avec Vibe</h1>
                <p class="mt-4 text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto">Un réseau social pour connecter, partager et interagir avec vos amis dans une expérience simple et dynamique.</p>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all">
                    <svg class="w-12 h-12 text-purple-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-purple-600 mb-2">Ajout d'Amis</h2>
                    <p class="text-gray-600">Envoyez des demandes, acceptez des amis et consultez votre liste d’amis en toute simplicité.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all">
                    <svg class="w-12 h-12 text-purple-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-purple-600 mb-2">Publications</h2>
                    <p class="text-gray-600">Partagez du texte et des images, modifiez ou supprimez vos posts à tout moment.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all">
                    <svg class="w-12 h-12 text-purple-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-purple-600 mb-2">Fil d'Actualités</h2>
                    <p class="text-gray-600">Découvrez les publications de vos amis, triées du plus récent au plus ancien.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-2 transition-all">
                    <svg class="w-12 h-12 text-purple-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-purple-600 mb-2">Likes & Commentaires</h2>
                    <p class="text-gray-600">Ajoutez des likes et commentez les posts pour interagir avec vos amis.</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Logo dans le footer -->
                    <div class="flex flex-col items-center md:items-start">
                        <svg class="h-12 w-28 mb-4" viewBox="0 0 140 70" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="35" cy="35" r="25" fill="none" stroke="#8b5cf6" stroke-width="6" stroke-linecap="round" stroke-dasharray="10 5"/>
                            <path d="M50 50 Q70 20 90 50" stroke="#8b5cf6" stroke-width="6" fill="none" stroke-linecap="round"/>
                            <text x="65" y="45" font-size="30" font-weight="700" fill="#d1d5db" transform="rotate(-10 65 45)">Vibe</text>
                        </svg>
                        <p class="text-sm text-center md:text-left">Connectez-vous et vibrez ensemble.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">À propos</h3>
                        <p class="text-sm">Vibe est un réseau social conçu pour connecter les gens et enrichir leurs interactions quotidiennes.</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Liens Utiles</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-sm hover:text-purple-400 transition-colors">Accueil</a></li>
                            <li><a href="#" class="text-sm hover:text-purple-400 transition-colors">Conditions d'utilisation</a></li>
                            <li><a href="#" class="text-sm hover:text-purple-400 transition-colors">Politique de confidentialité</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Contact</h3>
                        <ul class="space-y-2">
                            <li><a href="mailto:support@vibe.com" class="text-sm hover:text-purple-400 transition-colors">support@vibe.com</a></li>
                            <li><a href="#" class="text-sm hover:text-purple-400 transition-colors">FAQ</a></li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-center space-x-6 mt-8">
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.04c-5.5 0-10 4.5-10 10 0 4.4 2.9 8.2 6.9 9.5.5.1.7-.2.7-.5v-1.7c-2.8.6-3.4-1.3-3.4-1.3-.5-1.2-1.2-1.5-1.2-1.5-1-.7.1-.7.1-.7 1.1.1 1.7 1.1 1.7 1.1 1 1.7 2.6 1.2 3.2.9.1-.7.4-1.2.7-1.5-2.5-.3-5.1-1.3-5.1-5.7 0-1.3.5-2.4 1.2-3.2-.1-.3-.5-1.5.1-3.1 0 0 1-.3 3.3 1.2 1-.3 2-.4 3-.4s2 .1 3 .4c2.3-1.5 3.3-1.2 3.3-1.2.6 1.6.2 2.8.1 3.1.7.8 1.2 1.9 1.2 3.2 0 4.4-2.6 5.4-5.1 5.7.4.3.7.9.7 1.5v1.7c0 .3.2.6.7.5 4-1.3 6.9-5.1 6.9-9.5 0-5.5-4.5-10-10-10z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.5-4.5-10-10-10S2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.7c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.3 0-1.7.8-1.7 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
                    </a>
                </div>
                <p class="text-center text-sm text-gray-400 mt-8">© 2025 Vibe - Tous droits réservés </p>
            </div>
        </footer>
    </body>
</html>
