<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'pseudo',
        'bio',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sentDemandes()
    {
        return $this->hasMany(Demande::class, 'user_id');
    }

    public function receivedDemandes()
    {
        return $this->hasMany(Demande::class, 'receiver_id');
    }

    public function friends()
    {
        // Première partie : amis où l'utilisateur est l'expéditeur
        $sent = $this->belongsToMany(User::class, 'demandes', 'user_id', 'receiver_id')
            ->select('users.*', 'demandes.user_id as pivot_user_id', 'demandes.receiver_id as pivot_receiver_id', 'demandes.created_at as pivot_created_at', 'demandes.updated_at as pivot_updated_at')
            ->wherePivot('statut', 'accepted')
            ->withTimestamps();

        // Seconde partie : amis où l'utilisateur est le destinataire
        $received = $this->belongsToMany(User::class, 'demandes', 'receiver_id', 'user_id')
            ->select('users.*', 'demandes.user_id as pivot_user_id', 'demandes.receiver_id as pivot_receiver_id', 'demandes.created_at as pivot_created_at', 'demandes.updated_at as pivot_updated_at')
            ->wherePivot('statut', 'accepted')
            ->withTimestamps();

        return $sent->union($received);
    }

    public function mutualFriendsCount()
    {
        $userFriends = $this->friends()->pluck('users.id');
        $authFriends = auth()->user()->friends()->pluck('users.id');
        return $userFriends->intersect($authFriends)->count();
    }

    public function isOnline()
    {
        return cache()->has('user-is-online-' . $this->id);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://via.placeholder.com/50';
    }

    public function hasPendingRequestTo($userId)
    {
        return $this->sentDemandes()->where('receiver_id', $userId)
            ->where('statut', 'pending')
            ->exists();
    }

    public function isFriendWith($userId)
    {
        return $this->friends()->where('users.id', $userId)->exists();
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }
}
