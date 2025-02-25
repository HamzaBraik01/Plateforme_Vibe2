<p align="center">
  <img src="https://via.placeholder.com/150" alt="Vibe Logo" />
</p>

<h1 align="center">🌟 Vibe - Réseau Social</h1>

<p align="center">
  <strong>Une plateforme sociale interactive développée avec Laravel</strong><br>
  <em>Favoriser les connexions et les interactions dans un environnement simple et efficace</em>
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-10.x-red" alt="Laravel 10.x"></a>
  <a href="https://www.postgresql.org"><img src="https://img.shields.io/badge/PostgreSQL-16-blue" alt="PostgreSQL"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/Licence-MIT-green" alt="MIT License"></a>
</p>

---

## 🚀 Fonctionnalités

| Fonctionnalité            | Description                                                                 |
|---------------------------|-----------------------------------------------------------------------------|
| **👥 Ajout d’Amis**       | Envoi, acceptation/refus de demandes d’amis, liste des amis affichée.       |
| **✍️ Publications**       | Texte/image, modification/suppression, tri par date (récent → ancien).     |
| **📰 Fil d’Actualités**   | Publications des amis, triées chronologiquement (récent → ancien).         |
| **👤 Profils**            | Consultation des profils avec publications et interactions.                |
| **👍 Likes/Commentaires** | Ajout de likes avec compteur, commentaires affichés sous les posts.        |

### 🎉 Bonus
- 🔔 **Notifications** en temps réel (demandes d’amis, interactions).
- 📤 **Partage** des publications.
- ∞ **Pagination infinie** pour le fil d’actualités.
- #️⃣ **Hashtags** pour organiser/rechercher les publications.

---

## 🛠️ Prérequis

Avant de commencer, assurez-vous d’avoir installé :
- 🐘 **PHP** (>= 8.1)
- 📦 **Composer** (gestion des dépendances)
- ⚙️ **Laravel** (>= 10.x)
- 🗄️ **PostgreSQL** (base de données)
- 🌐 **Node.js & npm** (pour le frontend, si utilisé)
- 🔑 **Clé API** (ex. Pusher pour notifications)

---

## 📥 Installation

Suivez ces étapes pour configurer le projet localement :

1. **📋 Cloner le dépôt**
   ```bash
   git clone https://github.com/votre-utilisateur/vibe.git
   cd vibe
   ```

2. **📦 Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **🌐 Installer les dépendances frontend**
   ```bash
   npm install
   npm run dev
   ```

4. **⚙️ Configurer l’environnement**
   - Copiez `.env.example` vers `.env` :
     ```bash
     cp .env.example .env
     ```
   - Générez une clé d’application :
     ```bash
     php artisan key:generate
     ```

5. **🗄️ Configurer PostgreSQL**
   - Créez une base de données PostgreSQL.
   - Mettez à jour `.env` :
     ```plaintext
     DB_CONNECTION=postgresql
     DB_HOST=127.0.0.1
     DB_PORT=5432
     DB_DATABASE=vibe
     DB_USERNAME=votre_utilisateur
     DB_PASSWORD=votre_mot_de_passe
     ```

6. **🚀 Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

7. **🌍 Lancer le serveur**
   ```bash
   php artisan serve
   ```
   ➡️ Accédez à l’app : `http://localhost:8000`

---

## 📑 Structure du Projet

- **`app/Models`** : Modèles Eloquent (User, Post, Comment, etc.) 📋
- **`app/Http/Controllers`** : Contrôleurs pour la logique métier 🎮
- **`resources/views`** : Vues Blade pour l’interface 🖼️
- **`database/migrations`** : Migrations pour les tables 🗄️
- **`routes/web.php`** : Routes de l’application 🛤️

---

## 🧑‍💻 Développement

### Ajouter une fonctionnalité
1. Créez un modèle avec migration :
   ```bash
   php artisan make:model NomDuModele -m
   ```
2. Définissez les relations (ex. `hasMany`).
3. Ajoutez un contrôleur :
   ```bash
   php artisan make:controller NomDuController
   ```

### Exemple de tables
- **users** : `id`, `name`, `email`, `password`, etc.
- **friendships** : `id`, `user_id`, `friend_id`, `status`, `created_at`.
- **posts** : `id`, `user_id`, `content`, `image_path`, `created_at`.
- **likes** : `id`, `user_id`, `post_id`, `created_at`.
- **comments** : `id`, `user_id`, `post_id`, `content`, `created_at`.

---

## 🤝 Contribution

1. 🍴 Forkez le projet.
2. 🌿 Créez une branche : `git checkout -b feature/nouvelle-fonctionnalite`.
3. ✅ Commitez : `git commit -m "Ajout de X"`.
4. 🚀 Poussez : `git push origin feature/nouvelle-fonctionnalite`.
5. 📬 Ouvrez une Pull Request.
