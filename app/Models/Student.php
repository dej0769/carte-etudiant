<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        ];
    
        public function card() {
    return $this->hasOne(Card::class);
}
}
