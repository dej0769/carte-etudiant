<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'ine', 
        'nom', 
        'prenom', 
        'filiere', 
        'niveau', 
        'annee_academique', 
        'photo',
        'date_naissance', 
        'lieu_naissance',
        'card_number', 
        'expired_at', 
        'status'
        ];


        /**
     * Boot function pour gérer les événements du modèle
     */
    protected static function booted()
    {
        static::creating(function ($student) {
            // 1. Génération automatique du numéro de carte (Ex: 2026-UJK-08547)
            $annee = date('Y');
            $randomDigits = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $student->card_number = $annee . "-UJK-" . $randomDigits;

            // 2. Définir la date d'expiration (valide 1 an par défaut)
            if (!$student->expired_at) {
                $student->expired_at = Carbon::now()->addYear();
            }

            // 3. Statut par défaut
            if (!$student->status) {
                $student->status = 'active';
            }
        });
    }
    
        public function card() {
    return $this->hasOne(Card::class);
}
}
