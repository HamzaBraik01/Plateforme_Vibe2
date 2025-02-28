<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = ['user_id', 'receiver_id', 'statut'];

    const STATUT_PENDING = 'pending';
    const STATUT_ACCEPTED = 'accepted';
    const STATUT_REJECTED = 'rejected';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopePending($query)
    {
        return $query->where('statut', self::STATUT_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('statut', self::STATUT_ACCEPTED);
    }
}
