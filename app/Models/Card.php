<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id', 
        'numero_carte', 
        'status', 
        'qr_code_path', 
        'date_expiration'
        ];

public function student() {
    return $this->belongsTo(Student::class);
}
}
