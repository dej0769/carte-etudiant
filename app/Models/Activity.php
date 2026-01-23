<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Autorise le remplissage de ces colonnes
    protected $fillable = ['user_id', 'action', 'details'];

    /**
     * Relation : Une activité appartient à un utilisateur (l'admin)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

